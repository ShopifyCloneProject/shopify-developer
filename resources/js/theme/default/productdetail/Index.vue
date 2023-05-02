<template>
   <div>
        <!-- 2 for product detail settings -->
        <template v-for="(setting,index) in pagesettings" v-if="setting.page == 2">
              <DetailSection :product="data.productdetail" :advanceSettings="advanceSettings" :variants="data.variants" :variantsdetail="data.productvariantsdetail" :auth="auth" v-if="setting.sectionname=='detail' && setting.status == 1"></DetailSection>
              <PromocodeSection v-if="setting.sectionname=='promocode' && setting.status == 1"></PromocodeSection>
              <DescriptionSection :product="data.productdetail" :tags="data.tags" :variants="data.variants" :user="data.user" :reviews="data.reviews" :totalpages="data.totalPages" :totalrecords="data.totalRecords" :revieweditaccess="data.review_edit_access" v-if="setting.sectionname=='description' && setting.status == 1"></DescriptionSection>
              <RelatedSection :products="data.relatedProducts" :auth="auth" v-if="setting.sectionname=='related' && setting.status == 1"></RelatedSection>
        </template>
   </div>
</template>

<script>
import DetailSection from './DetailSection';
import PromocodeSection from './PromocodeSection';
import DescriptionSection from './DescriptionSection';
import RelatedSection from './RelatedSection';
export default {
    name: "ProductDetail",
    props: ['data', 'auth'],
    data() {
      return {
        pagesettings:[],
        advanceSettings:{
          detail_layout_position: "",
          short_description_limit: ""
        }
      }
    },
    created() {
      this.pagesettings = this.data.themeSettings.settings;
      this.advanceSettings = this.data.themeSettings.advanceSettings;
    },
    mounted(){
      document.title = this.data.productdetail.title;
    },
    methods: {
    },
    components: { 
      DetailSection,
      PromocodeSection,
      DescriptionSection,
      RelatedSection
    }
}
</script>