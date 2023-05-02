<template>
  <div>
    <draggable v-model="pageDatas" @end="stopDrag" :disabled="!enabled">
      <div v-for="(pageData, index) in pageDatas">
        <Header :list="pageData" :objCollections="objCollections" :objProducts="objProducts" :menudata="menudata"  v-if="pageData.sectionname == 'header'" @setDraggable="setDraggable"></Header>
        <Slider :list="pageData"  v-if="pageData.sectionname == 'slider'" @setDraggable="setDraggable"></Slider>
        <Accessories :list="pageData"  v-if="pageData.sectionname == 'accessories'" @setDraggable="setDraggable"></Accessories>
        <Logo :list="pageData"  v-if="pageData.sectionname == 'logo'" @setDraggable="setDraggable"></Logo>
        <Collection :list="pageData" :selectedCollection="selectedCollection" :allCollections="list.objCollections" v-if="pageData.sectionname == 'collection'" @setDraggable="setDraggable"></Collection>
        <Best :list="pageData"  v-if="pageData.sectionname == 'besttrends'" @setDraggable="setDraggable"></Best>
        <Newarrival :list="pageData"  v-if="pageData.sectionname == 'newarriaval'" @setDraggable="setDraggable"></Newarrival>
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
  import Slider from './slider';
  import Accessories from './accessories';
  import Logo from './logo';
  import Collection from './collection';
  import Best from './best';
  import Newarrival from './newarrival';
  import BestPrice from './bestprice';
  import Footer from './footer';
  import Copyright from './copyright';

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
        }
    },
    mounted() {
      this.pageDatas = this.list.pageSectionData;
      this.objCollections = this.list.objCollections;
      this.objProducts = this.list.objProducts;
      this.menudata = this.list.menudata;
      this.selectedCollection = this.list.selectedCollection;
    },
    methods: {
        savePages(){
            openLoader();
            let formData = {
                'pageData': this.pageDatas
            };
            this.$store.dispatch("pageModule/saveHomePages", formData)
            .then((res) => {
                closeLoader();
                if(res.response.status_code == 2092)
                {
                    successModal(res.response.message);
                    location.reload();
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
    components: {
      Header, Slider, Accessories, Logo, Collection, Best, Newarrival, BestPrice, Footer, Copyright, draggable
    },
  }
</script>

<style lang="scss" scoped>

</style>


