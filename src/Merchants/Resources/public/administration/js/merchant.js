(this.webpackJsonp=this.webpackJsonp||[]).push([["merchant"],{"3iPl":function(e){e.exports=JSON.parse('{"sw-cms":{"elements":{"merchantListing":{"label":"Händler-Listing"}},"blocks":{"commerce":{"merchantListing":{"label":"Händler-Listing"}}}}}')},"9xlu":function(e,n){e.exports='{% block sw_sales_channel_detail_base_general_input_footer_category %}\n    {% parent %}\n\n    <sw-entity-single-select\n            v-if="!!salesChannel.extensions.landingPage"\n            class="sw-sales-channel-detail__select-footer-category-id"\n            id="cmsPageId"\n            label="Landingpage layout"\n            v-model="salesChannel.extensions.landingPage.cmsPageId"\n            entity="cms_page"\n            required>\n    </sw-entity-single-select>\n{% endblock %}'},H01S:function(e,n,t){var s=t("JNLj");"string"==typeof s&&(s=[[e.i,s,""]]),s.locals&&(e.exports=s.locals);(0,t("SZ7m").default)("c01ca044",s,!0,{})},JNLj:function(e,n,t){},S1DF:function(e,n){e.exports='{% block sw_cms_element_merchant_listing_preview %}\n    <div class="sw-cms-el-preview-merchant-listing">\n        Preview\n    </div>\n{% endblock %}\n'},TwPW:function(e,n){e.exports='{% block sw_cms_block_merchant_listing_preview %}\n    <div class="sw-cms-preview-merchant-listing">\n        Preview\n    </div>\n{% endblock %}\n'},Y62v:function(e,n){e.exports='{% block sw_cms_element_merchant_listing_config %}\n<div class="sw-cms-el-config-merchant-listing">\n\n    {% block sw_cms_element_merchant_listing_config_layout_select %}\n    <sw-select-field :label="$tc(\'sw-cms.elements.productBox.config.label.layoutType\')" v-model="element.config.boxLayout.value">\n        {% block sw_cms_element_merchant_listing_config_layout_select_options %}\n        <option value="standard">{{ $tc(\'sw-cms.elements.productBox.config.label.layoutTypeStandard\') }}</option>\n        <option value="image">{{ $tc(\'sw-cms.elements.productBox.config.label.layoutTypeImage\') }}</option>\n        <option value="minimal">{{ $tc(\'sw-cms.elements.productBox.config.label.layoutTypeMinimal\') }}</option>\n        {% endblock %}\n    </sw-select-field>\n    {% endblock %}\n\n    {% block sw_cms_element_merchant_listing_config_info %}\n    <sw-alert variant="info">{{ $tc(\'sw-cms.elements.general.config.infoText.listingElement\') }}</sw-alert>\n    {% endblock %}\n</div>\n{% endblock %}\n'},aZs2:function(e,n,t){var s=t("o/XY");"string"==typeof s&&(s=[[e.i,s,""]]),s.locals&&(e.exports=s.locals);(0,t("SZ7m").default)("715dea7a",s,!0,{})},cQtu:function(e){e.exports=JSON.parse('{"sw-cms":{"elements":{"merchantListing":{"label":"Merchant-listing"}},"blocks":{"commerce":{"merchantListing":{"label":"Merchant-Listing"}}}}}')},hlh5:function(e,n){e.exports='{% block sw_cms_block_merchant_listing %}\n    <div class="sw-cms-block-merchant-listing">\n        <slot name="content"></slot>\n    </div>\n{% endblock %}\n'},nOh8:function(e,n){const{Component:t}=Shopware;t.override("sw-sales-channel-detail",{computed:{landingPageRepository(){return this.repositoryFactory.create("sales_channel_landing_page")}},methods:{loadSalesChannel(){this.isLoading=!0,this.salesChannelRepository.get(this.$route.params.id,Shopware.Context.api,this.getLoadSalesChannelCriteria()).then(e=>{if(this.salesChannel=e,this.salesChannel.maintenanceIpWhitelist||(this.salesChannel.maintenanceIpWhitelist=[]),!this.salesChannel.extensions.landingPage){const n=this.landingPageRepository.create(this.context);n.salesChannelId=e.id,this.salesChannel.extensions.landingPage=n}this.generateAccessUrl(),this.isLoading=!1})},onSave(){this.salesChannel.extensions.landingPage.cmsPageId?this.$super("onSave"):this.createNotificationError({title:this.$tc("sw-sales-channel.detail.titleSaveError"),message:"Please specify a landingpage layout"})}}})},"o/XY":function(e,n,t){},qATF:function(e,n,t){var s=t("zMQY");"string"==typeof s&&(s=[[e.i,s,""]]),s.locals&&(e.exports=s.locals);(0,t("SZ7m").default)("02e9004f",s,!0,{})},vrFq:function(e,n){e.exports='{% block sw_cms_element_merchant_listing %}\n    <div class="sw-cms-el-merchant-listing">\n        <div class="sw-cms-el-merchant-listing__content">\n            <sw-cms-el-product-box\n                :element="demoMerchantElement"\n                v-for="index in demoMerchantCount"\n                :key="index">\n            </sw-cms-el-product-box>\n        </div>\n\n        <div class="sw-cms-el-merchant-listing__pagination">\n            <div class="sw-cms-el-merchant-listing__pagination-entry">\n                <sw-icon name="small-arrow-small-left" size="20px"></sw-icon>\n            </div>\n\n            <div class="sw-cms-el-merchant-listing__pagination-entry">1</div>\n            <div class="sw-cms-el-merchant-listing__pagination-entry">2</div>\n            <div class="sw-cms-el-merchant-listing__pagination-entry">3</div>\n\n            <div class="sw-cms-el-merchant-listing__pagination-entry">\n                <sw-icon name="small-arrow-small-right" size="20px"></sw-icon>\n            </div>\n        </div>\n    </div>\n{% endblock %}\n{##}\n'},wWzi:function(e,n,t){"use strict";t.r(n);var s=t("hlh5"),i=t.n(s);const{Component:a}=Shopware;a.register("sw-cms-block-merchant-listing",{template:i.a});var l=t("TwPW"),o=t.n(l);t("H01S");const{Component:c}=Shopware;c.register("sw-cms-preview-merchant-listing",{template:o.a}),Shopware.Service("cmsService").registerCmsBlock({name:"merchant-listing",label:"sw-cms.blocks.commerce.merchantListing.label",category:"commerce",hidden:!1,removable:!0,component:"sw-cms-block-merchant-listing",previewComponent:"sw-cms-preview-merchant-listing",defaultConfig:{marginBottom:"20px",marginTop:"20px",marginLeft:"20px",marginRight:"20px",sizingMode:"boxed"},slots:{content:"merchant-listing"}});var r=t("vrFq"),m=t.n(r);t("qATF");const{Component:d,Mixin:g}=Shopware;d.register("sw-cms-el-merchant-listing",{template:m.a,mixins:[g.getByName("cms-element")],data:()=>({demoMerchantCount:8}),computed:{demoMerchantElement(){return{config:{boxLayout:{source:"static",value:this.element.config.boxLayout.value}},data:{merchant:{name:"Sample Merchant",email:"sample@merchant.com",website:"https://sample.merchant.com",description:"Lorem ipsum dolor sit amet, consetetur sadipscing elitr,\n                    sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,\n                    sed diam voluptua.".trim(),phone_number:"+49 00 1324 5678"}}}}},created(){this.createdComponent()},mounted(){this.mountedComponent()},methods:{createdComponent(){this.initElementConfig("merchant-listing")},mountedComponent(){this.$el.closest(".sw-cms-section").classList.contains("is--sidebar")&&(this.demoMerchantCount=6)}}});var h=t("Y62v"),p=t.n(h);const{Component:w,Mixin:u}=Shopware;w.register("sw-cms-el-config-merchant-listing",{template:p.a,mixins:[u.getByName("cms-element")],created(){this.createdComponent()},methods:{createdComponent(){this.initElementConfig("merchant-listing")}}});var v=t("S1DF"),_=t.n(v);t("aZs2");const{Component:f}=Shopware;f.register("sw-cms-el-preview-merchant-listing",{template:_.a}),Shopware.Service("cmsService").registerCmsElement({name:"merchant-listing",label:"sw-cms.elements.merchantListing.label",hidden:!1,removable:!0,component:"sw-cms-el-merchant-listing",previewComponent:"sw-cms-el-preview-merchant-listing",configComponent:"sw-cms-el-config-merchant-listing",defaultConfig:{boxLayout:{source:"static",value:"standard"}}});var b=t("9xlu"),x=t.n(b);const{Component:y}=Shopware;y.override("sw-sales-channel-detail-base",{template:x.a});t("nOh8");var C=t("3iPl"),S=t("cQtu"),k=Shopware.Locale;k.extend("de-DE",C),k.extend("en-GB",S)},zMQY:function(e,n,t){}},[["wWzi","runtime","vendors-node"]]]);