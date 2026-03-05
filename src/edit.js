/**
 * Monolith – Block editor component for the quick-view modal.
 *
 * Renders a static placeholder in the editor. The modal itself is
 * rendered server-side (render.php) and activated client-side (view.js).
 */

import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

/**
 * Editor placeholder for the Monolith Quick View Modal block.
 *
 * @return {JSX.Element}
 */
export default function Edit() {
	const blockProps = useBlockProps( {
		className: 'monolith-modal-editor-placeholder',
	} );

	return (
		<div { ...blockProps }>
			<div className="monolith-modal-editor-placeholder__inner">
				<span className="monolith-modal-editor-placeholder__icon dashicons dashicons-store" />
				<p className="monolith-modal-editor-placeholder__title">
					{ __( 'Monolith Quick View Modal', 'monolith' ) }
				</p>
				<p className="monolith-modal-editor-placeholder__description">
					{ __(
						'This block intercepts product clicks and shows a quick-view modal instead of navigating to the product page. It auto-inserts into every Product Collection block — no configuration needed.',
						'monolith'
					) }
				</p>
				<p className="monolith-modal-editor-placeholder__hint">
					{ __(
						'The modal is invisible in the editor and only activates on the front end.',
						'monolith'
					) }
				</p>
			</div>
		</div>
	);
}
