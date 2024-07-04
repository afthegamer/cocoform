import { __ } from '@wordpress/i18n';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import './editor.scss';

const Edit = () => {
	const blockProps = useBlockProps();

	return (
		<div {...blockProps}>
			<div className="cocoform-wrapper">
				<InnerBlocks />
			</div>
		</div>
	);
};

export default Edit;
