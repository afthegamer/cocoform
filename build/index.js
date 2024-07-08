(()=>{"use strict";var e,o={734:()=>{const e=window.wp.blocks,o=window.wp.blockEditor,t=window.wp.components,a=window.wp.element,c=window.React;(0,e.registerBlockType)("cocoform/contact-form",{title:"Formulaire de Contact",description:"Un bloc pour formulaire de contact.",category:"widgets",attributes:{formData:{type:"object",default:null}},edit:({attributes:e,setAttributes:r,clientId:l,isSelected:n})=>{const m=(0,o.useBlockProps)(),[i,s]=(0,a.useState)(null);return(0,a.useEffect)((()=>{fetch("/wp-json/cocoform/v1/form/brad").then((e=>e.json())).then((e=>{s(e),r({formData:e})})).catch((e=>console.error("Erreur:",e)))}),[]),i?(0,c.createElement)("div",{...m,className:"cocoform-wrapper"},n&&(0,c.createElement)(o.BlockControls,null,(0,c.createElement)(t.ToolbarGroup,null,(0,c.createElement)(t.ToolbarButton,{icon:"trash",label:"Supprimer le bloc",onClick:()=>wp.data.dispatch("core/block-editor").removeBlock(l)}),(0,c.createElement)(t.ToolbarButton,{icon:"move",label:"Déplacer le bloc",onClick:()=>wp.data.dispatch("core/block-editor").moveBlock(l)}))),(0,c.createElement)("form",{className:"cocoform-form"},i.fields.map(((e,o)=>(0,c.createElement)("div",{className:"cocoform-field",key:o},(0,c.createElement)("label",{className:"cocoform-label"},e.label),"text"===e.type&&(0,c.createElement)("input",{type:"text",className:"cocoform-input"}),"email"===e.type&&(0,c.createElement)("input",{type:"email",className:"cocoform-input"}),"number"===e.type&&(0,c.createElement)("input",{type:"number",className:"cocoform-input"}),"textarea"===e.type&&(0,c.createElement)("textarea",{className:"cocoform-textarea"}),"select"===e.type&&(0,c.createElement)("select",{className:"cocoform-select"},e.options.map(((e,o)=>(0,c.createElement)("option",{key:o,value:e},e))))))))):(0,c.createElement)("div",{...m},"Chargement du formulaire...")},save:({attributes:e})=>{const t=o.useBlockProps.save(),{formData:a}=e,{fields:r=[]}=a||{},l=r.find((e=>"Email"===e.label))||{},n=r.find((e=>"Objet"===e.label))||{};return(0,c.createElement)("div",{...t},(0,c.createElement)("style",null,"\n                .cocoform-wrapper {\n                    margin: 0 auto;\n                    padding: 20px;\n                    background: #f5f5f5;\n                    border-radius: 8px;\n                    max-width: 600px;\n                }\n                .cocoform-field {\n                    margin-bottom: 15px;\n                }\n                .cocoform-label {\n                    display: block;\n                    margin-bottom: 5px;\n                    font-weight: bold;\n                }\n                .cocoform-input,\n                .cocoform-select {\n                    width: 100%;\n                    padding: 10px;\n                    border: 1px solid #ccc;\n                    border-radius: 4px;\n                    box-sizing: border-box;\n                }\n                .cocoform-input:focus,\n                .cocoform-select:focus {\n                    border-color: #0073aa;\n                    box-shadow: 0 0 5px rgba(0, 115, 170, 0.5);\n                    outline: none;\n                }\n                @media (max-width: 600px) {\n                    .cocoform-wrapper {\n                        padding: 10px;\n                    }\n                    .cocoform-input,\n                    .cocoform-select {\n                        font-size: 14px;\n                        padding: 8px;\n                    }\n                }\n                @media (max-width: 400px) {\n                    .cocoform-input,\n                    .cocoform-select {\n                        font-size: 12px;\n                        padding: 6px;\n                    }\n                }\n            "),(0,c.createElement)("div",{className:"cocoform-wrapper"},(0,c.createElement)("div",{className:"cocoform-field"},(0,c.createElement)("label",{className:"cocoform-label"},l.label),(0,c.createElement)("input",{type:"email",className:"cocoform-input",value:l.default})),(0,c.createElement)("div",{className:"cocoform-field"},(0,c.createElement)("label",{className:"cocoform-label"},n.label),"text"===n.type?(0,c.createElement)("input",{type:"text",className:"cocoform-input",value:n.default}):(0,c.createElement)("select",{className:"cocoform-select"},n.options&&n.options.map(((e,o)=>(0,c.createElement)("option",{key:o,value:e},e))))),r.length>0&&r.filter((e=>"Email"!==e.label&&"Objet"!==e.label)).map(((e,o)=>(0,c.createElement)("div",{className:"cocoform-field",key:o},(0,c.createElement)("label",{className:"cocoform-label"},e.label),(0,c.createElement)("input",{type:e.type,className:"cocoform-input",readOnly:!0}))))))}})}},t={};function a(e){var c=t[e];if(void 0!==c)return c.exports;var r=t[e]={exports:{}};return o[e](r,r.exports,a),r.exports}a.m=o,e=[],a.O=(o,t,c,r)=>{if(!t){var l=1/0;for(s=0;s<e.length;s++){for(var[t,c,r]=e[s],n=!0,m=0;m<t.length;m++)(!1&r||l>=r)&&Object.keys(a.O).every((e=>a.O[e](t[m])))?t.splice(m--,1):(n=!1,r<l&&(l=r));if(n){e.splice(s--,1);var i=c();void 0!==i&&(o=i)}}return o}r=r||0;for(var s=e.length;s>0&&e[s-1][2]>r;s--)e[s]=e[s-1];e[s]=[t,c,r]},a.o=(e,o)=>Object.prototype.hasOwnProperty.call(e,o),(()=>{var e={57:0,350:0};a.O.j=o=>0===e[o];var o=(o,t)=>{var c,r,[l,n,m]=t,i=0;if(l.some((o=>0!==e[o]))){for(c in n)a.o(n,c)&&(a.m[c]=n[c]);if(m)var s=m(a)}for(o&&o(t);i<l.length;i++)r=l[i],a.o(e,r)&&e[r]&&e[r][0](),e[r]=0;return a.O(s)},t=globalThis.webpackChunkcocoform=globalThis.webpackChunkcocoform||[];t.forEach(o.bind(null,0)),t.push=o.bind(null,t.push.bind(t))})();var c=a.O(void 0,[350],(()=>a(734)));c=a.O(c)})();