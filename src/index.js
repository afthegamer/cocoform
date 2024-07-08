import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InnerBlocks, BlockControls } from '@wordpress/block-editor';
import { Toolbar, ToolbarButton } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import Edit from './edit';
import Save from './save';
import './editor.scss';  // Importation du CSS de l'éditeur
import './style.scss';   // Importation du CSS pour le front-end

registerBlockType('cocoform/contact-form', {
	title: 'Formulaire de Contact',
	description: 'Un bloc pour formulaire de contact.',
	category: 'widgets',
	attributes: {
		formData: {
			type: 'object',
			default: null,
		},
	},
	edit: Edit,
	/*edit: ({ attributes, setAttributes, clientId, isSelected }) => {
		console.log('Attributes in edit:', attributes);
		const blockProps = useBlockProps();
		const [formData, setFormData] = useState(null);

		useEffect(() => {
			fetch('/wp-json/cocoform/v1/form/brad')
				.then(response => response.json())
				.then(data => {
					setFormData(data);
					setAttributes({ formData: data });
				})
				.catch(error => console.error('Erreur:', error));
		}, []);

		if (!formData) {
			return <div {...blockProps}>Chargement du formulaire...</div>;
		}

		return (
			<div {...blockProps} className="cocoform-wrapper">
				{isSelected && (
					<BlockControls>
						<Toolbar>
							<ToolbarButton
								icon="trash"
								label="Supprimer le bloc"
								onClick={() => wp.data.dispatch('core/block-editor').removeBlock(clientId)}
							/>
							<ToolbarButton
								icon="move"
								label="Déplacer le bloc"
								onClick={() => wp.data.dispatch('core/block-editor').moveBlock(clientId, /!*fromIndex, toIndex*!/)}
							/>
						</Toolbar>
					</BlockControls>
				)}
				<form className="cocoform-form">
					{formData.fields.map((field, index) => (
						<div className="cocoform-field" key={index}>
							<label className="cocoform-label">{field.label}</label>
							{field.type === 'text' && <input type="text" className="cocoform-input" />}
							{field.type === 'email' && <input type="email" className="cocoform-input" />}
							{field.type === 'number' && <input type="number" className="cocoform-input" />}
							{field.type === 'textarea' && <textarea className="cocoform-textarea" />}
							{field.type === 'select' && (
								<select className="cocoform-select">
									{field.options.map((option, idx) => (
										<option key={idx} value={option}>{option}</option>
									))}
								</select>
							)}
						</div>
					))}
				</form>
			</div>
		);
	},*/
	save: Save,
	// save: ({ attributes }) => {
	// 	console.log('Attributes in save:', attributes);
	// 	console.log("brad");
	// 	const blockProps = useBlockProps.save();
	// 	return (
	// 		<div {...blockProps}>
	// 			<div className="cocoform-wrapper">
	// 				<input type="text" value={attributes.email} readOnly />
	// 				<input type="text" value={attributes.message} readOnly />
	// 				<InnerBlocks.Content />
	// 			</div>
	// 		</div>
	// 	);
	// },
});
