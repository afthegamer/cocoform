(()=>{"use strict";const e=window.React,t=window.wp.blocks,a=window.wp.components;(0,t.registerBlockType)("cocoform/contact-form",{title:"Formulaire de Contact",description:"Un bloc pour formulaire de contact.",category:"widgets",attributes:{email:{type:"string",default:""},message:{type:"string",default:""}},edit:({attributes:t,setAttributes:l})=>(0,e.createElement)("div",null,(0,e.createElement)(a.TextControl,{label:"Email",value:t.email,onChange:e=>l({email:e})}),(0,e.createElement)(a.TextControl,{label:"Message",value:t.message,onChange:e=>l({message:e})})),save:({attributes:t})=>(0,e.createElement)("div",null,(0,e.createElement)("input",{type:"text",value:t.email,readOnly:!0}),(0,e.createElement)("textarea",{value:t.message,readOnly:!0}))})})();