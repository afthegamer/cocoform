import { registerBlockType } from '@wordpress/blocks';
import { TextControl } from '@wordpress/components';

registerBlockType('cocoform/contact-form', {
	title: 'Formulaire de Contact',
	description: 'Un bloc pour formulaire de contact.',
	category: 'widgets',
	attributes: {
		email: {
			type: 'string',
			default: ''
		},
		message: {
			type: 'string',
			default: ''
		}
	},
	edit: ({ attributes, setAttributes }) => {
		return (
			<div>
				<TextControl
					label="Email"
					value={attributes.email}
					onChange={(email) => setAttributes({ email })}
				/>
				<TextControl
					label="Message"
					value={attributes.message}
					onChange={(message) => setAttributes({ message })}
				/>
			</div>
		);
	},
	save: ({ attributes }) => {
		return (
			<div>
				<input type="text" value={attributes.email} readOnly />
				<input value={attributes.message} readOnly></input>
			</div>
		);

	},
});
