/**
 * CTA Block
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, RichText, InnerBlocks, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ColorPalette } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('ramboeck/cta', {
	edit: ({ attributes, setAttributes }) => {
		const { title, text, backgroundColor } = attributes;

		const blockProps = useBlockProps({
			className: 'cta',
			style: { backgroundColor: backgroundColor || '#3b82f6' },
		});

		return (
			<>
				<InspectorControls>
					<PanelBody title={__('Farben', 'ramboeck')}>
						<p>{__('Hintergrundfarbe', 'ramboeck')}</p>
						<ColorPalette
							value={backgroundColor}
							onChange={(value) => setAttributes({ backgroundColor: value })}
						/>
					</PanelBody>
				</InspectorControls>

				<section {...blockProps}>
					<div className="cta__container" style={{ maxWidth: '800px', margin: '0 auto', padding: '48px 24px', textAlign: 'center', color: '#fff' }}>
						<RichText
							tagName="h2"
							className="cta__title"
							placeholder={__('Titel eingeben...', 'ramboeck')}
							value={title}
							onChange={(value) => setAttributes({ title: value })}
							style={{ color: 'inherit', marginBottom: '12px' }}
						/>
						<RichText
							tagName="p"
							className="cta__text"
							placeholder={__('Text eingeben...', 'ramboeck')}
							value={text}
							onChange={(value) => setAttributes({ text: value })}
							style={{ opacity: 0.9, marginBottom: '24px' }}
						/>
						<div className="cta__actions">
							<InnerBlocks allowedBlocks={['core/buttons']} template={[['core/buttons']]} />
						</div>
					</div>
				</section>
			</>
		);
	},

	save: () => <InnerBlocks.Content />,
});
