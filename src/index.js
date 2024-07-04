// import { registerBlockType } from '@wordpress/blocks';
// import { TextControl } from '@wordpress/components';


import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { useEffect, useState } from '@wordpress/element';
import './editor.scss';

registerBlockType('create-block/cocoform', {
	title: 'Coco Form',
	icon: 'smiley',
	category: 'widgets',
	edit() {
		const blockProps = useBlockProps();
		const [formData, setFormData] = useState(null);

		useEffect(() => {
			fetch('/wp-json/cocoform/v1/form/brad')
				.then(response => response.json())
				.then(data => setFormData(data))
				.catch(error => console.error('Erreur:', error));
		}, []);

		if (!formData) {
			return <div {...blockProps}>Chargement du formulaire...</div>;
		}

		return (
			<div {...blockProps} className="cocoform-wrapper">
				<form className="cocoform-form">
					{formData.fields.map((field, index) => (
						<div className="cocoform-field" key={index}>
							<label className="cocoform-label">{field.label}</label>
							{field.type === 'text' && <input type="text" className="cocoform-input" />}
							{field.type === 'email' && <input type="email" className="cocoform-input" />}
							{field.type === 'number' && <input type="number" className="cocoform-input" />}
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
	},
	save() {
		return null; // Le rendu est fait côté client
	},
});

