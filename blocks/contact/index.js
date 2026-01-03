/**
 * Contact Form Block
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('ramboeck/contact', {
	edit: ({ attributes, setAttributes }) => {
		const { recipientEmail, successMessage } = attributes;
		const blockProps = useBlockProps({ className: 'contact-form' });

		return (
			<>
				<InspectorControls>
					<PanelBody title={__('Einstellungen', 'ramboeck')}>
						<TextControl
							label={__('Empfänger E-Mail', 'ramboeck')}
							value={recipientEmail}
							onChange={(v) => setAttributes({ recipientEmail: v })}
							help={__('Leer lassen für Admin-E-Mail', 'ramboeck')}
						/>
						<TextareaControl
							label={__('Erfolgsmeldung', 'ramboeck')}
							value={successMessage}
							onChange={(v) => setAttributes({ successMessage: v })}
						/>
					</PanelBody>
				</InspectorControls>

				<div {...blockProps}>
					<form className="contact-form__form" onSubmit={(e) => e.preventDefault()}>
						<div className="contact-form__grid" style={{ display: 'grid', gridTemplateColumns: 'repeat(2, 1fr)', gap: '16px', marginBottom: '16px' }}>
							<div className="contact-form__field">
								<label>{__('Name', 'ramboeck')} *</label>
								<input type="text" disabled style={{ padding: '12px', border: '1px solid #e2e8f0', borderRadius: '6px' }} />
							</div>
							<div className="contact-form__field">
								<label>{__('E-Mail', 'ramboeck')} *</label>
								<input type="email" disabled style={{ padding: '12px', border: '1px solid #e2e8f0', borderRadius: '6px' }} />
							</div>
							<div className="contact-form__field">
								<label>{__('Telefon', 'ramboeck')}</label>
								<input type="tel" disabled style={{ padding: '12px', border: '1px solid #e2e8f0', borderRadius: '6px' }} />
							</div>
							<div className="contact-form__field">
								<label>{__('Betreff', 'ramboeck')}</label>
								<input type="text" disabled style={{ padding: '12px', border: '1px solid #e2e8f0', borderRadius: '6px' }} />
							</div>
						</div>
						<div className="contact-form__field" style={{ marginBottom: '16px' }}>
							<label>{__('Nachricht', 'ramboeck')} *</label>
							<textarea rows="5" disabled style={{ padding: '12px', border: '1px solid #e2e8f0', borderRadius: '6px', width: '100%' }}></textarea>
						</div>
						<div className="contact-form__field" style={{ marginBottom: '16px' }}>
							<label style={{ display: 'flex', alignItems: 'center', gap: '8px', fontWeight: 'normal' }}>
								<input type="checkbox" disabled />
								{__('Ich habe die Datenschutzerklärung gelesen...', 'ramboeck')} *
							</label>
						</div>
						<button type="submit" className="button" disabled style={{ padding: '12px 24px', background: '#3b82f6', color: '#fff', border: 'none', borderRadius: '6px', fontWeight: '600' }}>
							{__('Nachricht senden', 'ramboeck')}
						</button>
					</form>
				</div>
			</>
		);
	},
	save: () => null,
});
