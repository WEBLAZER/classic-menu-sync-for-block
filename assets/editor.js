(function (wp) {
	var addFilter = wp.hooks.addFilter;
	var createHigherOrderComponent = wp.compose.createHigherOrderComponent;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var PanelBody = wp.components.PanelBody;
	var SelectControl = wp.components.SelectControl;
	var Button = wp.components.Button;
	var ToggleControl = wp.components.ToggleControl;
	var Notice = wp.components.Notice;
	var __ = wp.i18n.__;
	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var useState = wp.element.useState;
	var useEffect = wp.element.useEffect;
	var apiFetch = wp.apiFetch;

	function getMenuOptions() {
		if (
			window.menuSyncForNavigationBlock &&
			Array.isArray(window.menuSyncForNavigationBlock.menus)
		) {
			return [
				{ label: __('Do not sync with classic menu', 'menu-sync-for-navigation-block'), value: '' },
				...window.menuSyncForNavigationBlock.menus
			];
		}
		return [{ label: __('Do not sync with classic menu', 'menu-sync-for-navigation-block'), value: '' }];
	}

	var withNavigationAutoSync = createHigherOrderComponent(function (BlockEdit) {
		return function (props) {
			var _useState = useState('');
			var selectedMenuId = _useState[0];
			var setSelectedMenuId = _useState[1];

			var _useState2 = useState(false);
			var isLoading = _useState2[0];
			var setIsLoading = _useState2[1];

			var _useState3 = useState('');
			var statusMessage = _useState3[0];
			var setStatusMessage = _useState3[1];

			if (props.name !== 'core/navigation') {
				return el(BlockEdit, props);
			}

			var attributes = props.attributes || {};
			var setAttributes = props.setAttributes;

			// Get navigation post ID from the ref attribute
			var navigationPostId = attributes.ref;

			useEffect(function () {
				if (!navigationPostId) {
					return;
				}

				// Load current sync settings using our custom API
				apiFetch({
					path: '/menu-sync-for-navigation-block/v1/settings/' + navigationPostId,
					method: 'GET'
				}).then(function (settings) {
					if (settings.linked_menu_id) {
						setSelectedMenuId(settings.linked_menu_id.toString());
					}
				}).catch(function (error) {
					console.error('Failed to load navigation settings:', error);
				});
			}, [navigationPostId]);

			function handleValidate() {
				if (!navigationPostId) {
					setStatusMessage(__('Navigation post not found.', 'menu-sync-for-navigation-block'));
					return;
				}

				setIsLoading(true);
				setStatusMessage('');

				// Save the menu selection and enable/disable auto-sync
				var linkedMenuId = selectedMenuId ? parseInt(selectedMenuId) : null;
				var autoSyncEnabled = selectedMenuId !== '';

				apiFetch({
					path: '/menu-sync-for-navigation-block/v1/settings/' + navigationPostId,
					method: 'POST',
					data: {
						linked_menu_id: linkedMenuId,
						auto_sync_enabled: autoSyncEnabled
					}
				}).then(function () {
					if (selectedMenuId) {
						// Sync the navigation content if a menu is selected
						return apiFetch({
							path: '/menu-sync-for-navigation-block/v1/sync/' + navigationPostId + '/' + selectedMenuId,
							method: 'POST'
						});
					} else {
						// Just return success if no menu selected (disable sync)
						return { success: true };
					}
				}).then(function (response) {
					if (selectedMenuId) {
						setStatusMessage(__('Navigation synchronized successfully!', 'menu-sync-for-navigation-block'));
					} else {
						setStatusMessage(__('Menu synchronization disabled.', 'menu-sync-for-navigation-block'));
					}

					// Refresh the editor to show updated content after a delay
					setTimeout(function () {
						window.location.reload();
					}, 1500);
				}).catch(function (error) {
					setStatusMessage(__('Operation failed: ', 'menu-sync-for-navigation-block') + (error.message || error));
				}).finally(function () {
					setIsLoading(false);
				});
			}

			// Only show controls if we have a navigation post
			if (!navigationPostId) {
				return el(BlockEdit, props);
			}

			return el(
				Fragment,
				null,
				el(BlockEdit, props),
				el(
					InspectorControls,
					{ key: 'menu-sync-for-navigation-block-controls', group: 'list' },
					el(
						PanelBody,
						{
							title: __('Menu Synchronization', 'menu-sync-for-navigation-block'),
							initialOpen: false
						},
						statusMessage && el(
							Notice,
							{
								status: statusMessage.includes('failed') || statusMessage.includes('error') ? 'error' : 'success',
								isDismissible: true,
								onRemove: function () { setStatusMessage(''); }
							},
							statusMessage
						),
						el(SelectControl, {
							label: __('Synchronization Settings', 'menu-sync-for-navigation-block'),
							value: selectedMenuId,
							options: getMenuOptions(),
							onChange: function (value) {
								setSelectedMenuId(value);
							},
							help: selectedMenuId 
								? __('This navigation will auto-sync with the selected menu when it changes.', 'menu-sync-for-navigation-block')
								: __('No synchronization - manage this navigation manually.', 'menu-sync-for-navigation-block')
						}),
						el(Button, {
							variant: 'primary',
							onClick: handleValidate,
							disabled: isLoading,
							isBusy: isLoading
						}, isLoading ? __('Applying...', 'menu-sync-for-navigation-block') : __('Apply Settings', 'menu-sync-for-navigation-block'))
					)
				)
			);
		};
	}, 'withNavigationAutoSync');

	addFilter(
		'editor.BlockEdit',
		'menu-sync-for-navigation-block/with-navigation-auto-sync',
		withNavigationAutoSync,
		5
	);
})(window.wp);