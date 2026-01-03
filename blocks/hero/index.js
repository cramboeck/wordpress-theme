/**
 * Hero Block
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InnerBlocks, RichText, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, SelectControl, RangeControl, Button, ColorPalette } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('ramboeck/hero', {
	edit: ({ attributes, setAttributes }) => {
		const {
			title,
			subtitle,
			alignment,
			fullHeight,
			backgroundType,
			backgroundColor,
			backgroundImage,
			overlayOpacity,
			textColor,
		} = attributes;

		const blockProps = useBlockProps({
			className: `hero hero--align-${alignment} ${fullHeight ? 'hero--full' : ''} ${backgroundImage?.url ? 'hero--has-background' : ''}`,
			style: {
				backgroundColor: backgroundColor || undefined,
				backgroundImage: backgroundImage?.url ? `url(${backgroundImage.url})` : undefined,
				'--hero-text-color': textColor || undefined,
			},
		});

		const ALLOWED_BLOCKS = ['core/button', 'core/buttons'];

		return (
			<>
				<InspectorControls>
					<PanelBody title={__('Layout', 'ramboeck')}>
						<SelectControl
							label={__('Ausrichtung', 'ramboeck')}
							value={alignment}
							options={[
								{ label: __('Links', 'ramboeck'), value: 'left' },
								{ label: __('Zentriert', 'ramboeck'), value: 'center' },
								{ label: __('Rechts', 'ramboeck'), value: 'right' },
							]}
							onChange={(value) => setAttributes({ alignment: value })}
						/>
						<ToggleControl
							label={__('Volle Höhe', 'ramboeck')}
							checked={fullHeight}
							onChange={(value) => setAttributes({ fullHeight: value })}
						/>
					</PanelBody>

					<PanelBody title={__('Hintergrund', 'ramboeck')}>
						<SelectControl
							label={__('Typ', 'ramboeck')}
							value={backgroundType}
							options={[
								{ label: __('Farbe', 'ramboeck'), value: 'color' },
								{ label: __('Bild', 'ramboeck'), value: 'image' },
							]}
							onChange={(value) => setAttributes({ backgroundType: value })}
						/>

						{backgroundType === 'color' && (
							<ColorPalette
								value={backgroundColor}
								onChange={(value) => setAttributes({ backgroundColor: value })}
							/>
						)}

						{backgroundType === 'image' && (
							<>
								<MediaUploadCheck>
									<MediaUpload
										onSelect={(media) => setAttributes({ backgroundImage: { id: media.id, url: media.url } })}
										allowedTypes={['image']}
										value={backgroundImage?.id}
										render={({ open }) => (
											<Button onClick={open} variant="secondary" style={{ marginBottom: '16px' }}>
												{backgroundImage?.url ? __('Bild ändern', 'ramboeck') : __('Bild auswählen', 'ramboeck')}
											</Button>
										)}
									/>
								</MediaUploadCheck>

								{backgroundImage?.url && (
									<>
										<img src={backgroundImage.url} alt="" style={{ maxWidth: '100%', marginBottom: '16px' }} />
										<RangeControl
											label={__('Overlay Deckkraft', 'ramboeck')}
											value={overlayOpacity}
											onChange={(value) => setAttributes({ overlayOpacity: value })}
											min={0}
											max={100}
										/>
									</>
								)}
							</>
						)}
					</PanelBody>

					<PanelBody title={__('Farben', 'ramboeck')}>
						<p>{__('Textfarbe', 'ramboeck')}</p>
						<ColorPalette
							value={textColor}
							onChange={(value) => setAttributes({ textColor: value })}
						/>
					</PanelBody>
				</InspectorControls>

				<section {...blockProps}>
					{backgroundImage?.url && (
						<div className="hero__overlay" style={{ opacity: overlayOpacity / 100 }}></div>
					)}

					<div className="hero__container">
						<div className="hero__content">
							<RichText
								tagName="h1"
								className="hero__title"
								placeholder={__('Titel eingeben...', 'ramboeck')}
								value={title}
								onChange={(value) => setAttributes({ title: value })}
							/>
							<RichText
								tagName="p"
								className="hero__subtitle"
								placeholder={__('Untertitel eingeben...', 'ramboeck')}
								value={subtitle}
								onChange={(value) => setAttributes({ subtitle: value })}
							/>
							<div className="hero__actions">
								<InnerBlocks allowedBlocks={ALLOWED_BLOCKS} template={[['core/buttons']]} />
							</div>
						</div>
					</div>
				</section>
			</>
		);
	},

	save: ({ attributes }) => {
		return <InnerBlocks.Content />;
	},
});
