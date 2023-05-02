<template>
  <div>
    <draggable v-model="pageDatas" @end="stopDrag" :disabled="!enabled">
      <div v-for="(pageData, index) in pageDatas">
          <Header :list="pageData" v-if="pageData.sectionname == 'header'" @setDraggable="setDraggable"></Header>
          <Detail :list="pageData" :advanceSettings="advanceSettings" v-if="pageData.sectionname == 'detail'" @setDraggable="setDraggable"></Detail>
          <Promocode :list="pageData" v-if="pageData.sectionname == 'promocode'" @setDraggable="setDraggable"></Promocode>
          <Description :list="pageData" v-if="pageData.sectionname == 'description'" @setDraggable="setDraggable"></Description>
          <Related :list="pageData" v-if="pageData.sectionname == 'related'" @setDraggable="setDraggable"></Related>
          <Footer :list="pageData"  v-if="pageData.sectionname == 'footer'" @setDraggable="setDraggable"></Footer>
      </div>
    </draggable>
    <div class="form-group text-right" >
        <button class="btn btn-primary waves-effect fixed-right-bottom" type="buttom" @click.prevent="savePages">{{ lang.global.save }}</button>
    </div>
  </div>
</template>

<script>
  import Header from './header';
  import Footer from './footer';
  import Detail from './detail';
  import Description from './description';
  import Promocode from './promocode';
  import Related from './related';
  import draggable from 'vuedraggable'

  export default {
    props: ['list'],
    name:'mainpage',
    data() {
      return {
          pageDatas: [],
          objCollections: [],
          objProducts: [],
          menudata: [],
          enabled: false,
          advanceSettings:{
            detail_layout_position: "",
            short_description_limit: ""
          }
        }
    },
    mounted() {
      this.pageDatas = this.list.pageSectionData;
      this.objCollections = this.list.objCollections;
      this.objProducts = this.list.objProducts;
      this.menudata = this.list.menudata;
      this.advanceSettings = this.list.advanceSettings;
    },
    methods: {
        savePages(){
          openLoader();
          let formData = {
              'pageData': this.pageDatas,
              'advanceSettings': this.advanceSettings
          };
          this.$store.dispatch("pageModule/saveDetailPageSettings", formData)
          .then((res) => {
              closeLoader();
              if(res.response.status_code == 2092)
              {
                  successModal(res.response.message);
                  // location.reload();
              }
          })
          .catch((err) => {
            closeLoader();
            errorModal(err.response.message);
          });
        },
        stopDrag(){
          feather.replace();
        },
        setDraggable(status){
          this.enabled = status;
        },
    },
    computed: {
    },
    components: {
      Detail, Description, Related, draggable, Header, Footer, Promocode
    },
  }
</script>

<style lang="scss" scoped>

</style>


