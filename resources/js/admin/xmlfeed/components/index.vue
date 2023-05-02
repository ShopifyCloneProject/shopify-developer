<template>
  <div id="input-sizing">
    <ValidationObserver ref="xmlForm" v-slot="{ handleSubmit }">
        <form id="frmGiftCards" @submit.prevent="handleSubmit(generateXML())">
          <div class="row">
            <div class="col-md-12 col-12">
                  <!-- Basic details start -->
                <div class="card">
                    <div class="card-body">
                        <h6 class="store-title">XML Feed</h6>
                       <ValidationProvider  name="XML Feed Name" rules="required" v-slot="{ errors }">
                              <div class="form-group">
                                  <label for="largeInput">XML Feed name <span class="text-danger">*</span></label>
                                  <input class="form-control" type="text" name="title" v-model="XMLFeedName">
                                    <p class="text-danger">{{ errors[0] }}</p>
                              </div>
                        </ValidationProvider>
                        <div>
                            <div class="demo-inline-spacing">
                                <div class="custom-control custom-radio">
                                  <input type="radio" id="xmlFeedRadio2" name="xmlFeedRadio" class="custom-control-input" value="0" v-model.number="XMLOption" @change="resetOption()"/>
                                  <label class="custom-control-label" for="xmlFeedRadio2">Products</label>
                                </div>
                                <div class="custom-control custom-radio">
                                  <input type="radio" id="xmlFeedRadio4" name="xmlFeedRadio" class="custom-control-input" value="1" v-model.number="XMLOption" @change="resetOption()"/>
                                  <label class="custom-control-label" for="xmlFeedRadio4">Collections</label>
                                </div>
                            </div>
                        </div>

                        <div id="infinite-list" v-if="XMLOption == 0">
                            <p class="card-text mt-2 mb-0">Select product option for generate XML feed.</p>
                            <div class="demo-inline-spacing mb-2">
                                <div class="custom-control custom-radio">
                                  <input type="radio" id="productOption1" name="productOption" class="custom-control-input" value="0" v-model.number="productOption" />
                                  <label class="custom-control-label" for="productOption1">All Products</label>
                                </div>
                                <div class="custom-control custom-radio">
                                  <input type="radio" id="productOption2" name="productOption" class="custom-control-input" value="1" v-model.number="productOption" @change="loadMore()"/>
                                  <label class="custom-control-label" for="productOption2">Manual Select Product</label>
                                </div>
                            </div>
                            <div v-show="productOption == 1" id="manualProductSection">
                                <div class="input-group input-group-merge mb-2">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon-search2"><i data-feather="search"></i></span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Search..." aria-label="Search..." aria-describedby="basic-addon-search2" v-model="searchVal" @keyup="loadMoreCall()"/>
                                </div>
                                <div id="browse-product-list">
                                    <div class="d-flex browse-list py-1 border-bottom" v-for="(product, index) in allproducts">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input mt-1" :id="`customCheck_`+ product.id" style="opacity:1" :value="product.id" v-model="selectedProductList"/>
                                        </div>
                                        <div class="product-thumb mx-1">
                                            <img :src="product.medias[0].image_src[1]" :alt="product.title" v-if="product.medias.length > 0" height="50" width="50" @error="setAltImg">
                                            <img src="" :alt="product.title" height="50" width="50" @error="setAltImg" v-else>
                                        </div>
                                        <div class="product-title">
                                            {{product.title}}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center py-1" v-if="totalProducts != allproducts.length">
                                  <a class="pointer" @click.prevent="loadAllProducts()">Load all products</a>
                                </div>
                            </div>
                        </div>

                        <div id="collections-list" v-if="XMLOption == 1">
                            <p class="card-text mt-2 mb-0">Select collection or products option for generate XML feed.</p>
                            <div class="demo-inline-spacing mb-2">
                                <div class="custom-control custom-radio">
                                  <input type="radio" id="collectionOption1" name="collectionOption" class="custom-control-input" value="0" v-model.number="productOption"/>
                                  <label class="custom-control-label" for="collectionOption1">All Collection</label>
                                </div>
                                <div class="custom-control custom-radio">
                                  <input type="radio" id="collectionOption2" name="collectionOption" class="custom-control-input" value="1" v-model.number="productOption" @change="callGetCollection"/>
                                  <label class="custom-control-label" for="collectionOption2">Manual Select Collection/Product</label>
                                </div>
                            </div>
                            <div v-if="productOption ==  1">
                                <div id="browse-collections-list">
                                    <div>
                                      <DxTreeView
                                        id="producttreeview"
                                        :select-nodes-recursive="true"
                                        :items="collections"
                                        :search-enabled="true"
                                        :search-mode="searchMode"
                                        :data-source="collections"
                                        data-field="id"
                                        :max-height="500"
                                        show-check-boxes-mode="normal"
                                        key-expr="id"
                                        @selection-changed="onSelectionChanged"
                                      />
                                  </div>
                                </div>
                            </div>
                        </div>
                         <div class="row mt-1" v-for="(chooseOption,index) in chooseOptions">
                            <div class="col-5">
                              <select :name="'choose1'+index" :id="'firstchoose'+index" :data-index="index"  class="selecthandlefirst choose w-75" v-model="chooseOption.choose1" >
                                <optgroup v-for="(group, index) in optionGroups1" :label="group.displaycolumnname">
                                  <option v-for="option in group.relations" :value="option.id">
                                    {{ option.displaycolumnname }} [{{ option.columnname }}]
                                  </option>
                                </optgroup>
                              </select>
                            </div>
                            <div class="col-5">
                              <select :name="'choose2'+index" :id="'secondchoose'+index" :data-index="index"  class="selecthandlesecond choose w-75" v-model="chooseOption.choose2" >
                                  <option v-for="(secondsection,index) in optionGroups2" :value="secondsection.id">{{ secondsection.displaycolumnname }} [{{ secondsection.columnname }}]</option>
                              </select>
                            </div>
                            <div class="col-1" >
                                <a href="javascript:void(0)" @click.prevent="handleField(index,'remove')" v-if="chooseOptions.length > 1">
                                  <span><i class="feather-30" data-feather='minus-circle'></i></span>
                                </a>
                            </div>
                            <div class="col-1" >
                               <a href="javascript:void(0)" @click.prevent="handleField(index,'add')"  > 
                                <span  v-if="(chooseOptions.length - 1) == index"><i class="feather-30" data-feather='plus-circle'></i></span>
                               </a>
                             </div>
                          </div>
                    </div>
                </div>
              <!-- Basic details end -->
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="form-group text-right" >
                <button class="btn btn-primary waves-effect" type="submit">Generate XML file</button>
              </div>
            </div>
          </div>

      </form>
    </ValidationObserver>

  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { DxTreeView, DxSelection } from 'devextreme-vue/tree-view';
import DxSelectBox from 'devextreme-vue/select-box';
import 'devextreme/dist/css/dx.greenmist.css';

export default {
    props: ['data'],
    name:'xmlfeed',
    data() {
      return {
        XMLOption:0,
        productOption: 0,
        nextItem: 0,
        searchVal:'',
        allproducts:[],
        collections:[],
        selectedProductList:[],
        selectedCollectionList:[],
        client_id: CLIENT_ID,
        totalProducts:0,
        searchMode: 'contains',
        chooseOptions:[],
        optionGroups1: [],
        optionGroups2: [],
        XMLFeedName: null,
      }
    },
    components: {
      DxSelectBox,
      DxTreeView
    },
    computed: {
        noImage(){
            return '/assets/images/no-image.jpg';
        },
    },
    mounted(){
        // this.collections = this.data.collections;

        // Detect when scrolled to bottom.
        const listElm = document.querySelector('#browse-product-list');
        listElm.addEventListener('scroll', e => {
            if(listElm.scrollTop + listElm.clientHeight >= listElm.scrollHeight) 
            {
                if(this.totalProducts != this.allproducts.length){
                    var sHeight = document.getElementById("browse-product-list").scrollHeight;
                    document.getElementById("browse-product-list").scrollTop = sHeight - (sHeight / 2);

                    let section = $('#infinite-list');
                    blockSection(section);
                    clearTimeout(this.timer);
                    this.timer = setTimeout(() => {
                       this.loadMore();
                    }, 1000)
                }
            }
        });

        let self = this;
        self.chooseOptions = self.data.objXmlFeed;
        self.optionGroups1 = self.data.objSection1;
        self.optionGroups2 = self.data.objSection2;
        setTimeout(function(){ self.setWithSelect(); },500); 
    },
    methods: {
        resetOption(){
            this.productOption = 0;
            this.selectedProductList = [];
            this.selectedCollectionList = [];
        },
        loadMoreCall(){
            this.nextItem = 0;
            this.allproducts = [];
            this.timer = setTimeout(() => {
                this.loadMore();
            }, 2000)
        },
        loadMore(){
            let section = $('#infinite-list');
            blockSection(section);
            let data = {
              'number': this.nextItem,
              'search': this.searchVal,
            };
            this.$store.dispatch("xmlfeedModule/loadMoreProduct", data)
            .then((res) => {
                if (res.response.status_code == 2044) {
                    let self = this;
                    if(this.nextItem == 'all'){
                         this.allproducts = [];
                    }
                    this.totalProducts =  res.response.data.total;
                    res.response.data.products.forEach(product => {
                        self.allproducts.push(product);
                    });
                    this.nextItem = this.nextItem + 20;
                }
                else{
                    errorModal(res.response.message);
                  }
                unblockSection(section);
            })
            .catch((err) => {
                unblockSection(section);
                errorModal(err.response.message);
            });
        },
        loadAllProducts(){
            this.nextItem = 'all';
            this.loadMore();
        },
        setAltImg(event){
            event.target.src = this.noImage;
        },
        callGetCollection(){
            if(this.collections.length == 0){
                this.getCollectionProducts();
            }
        },
        getCollectionProducts(){
            let section = $('#collections-list');
            blockSection(section);
            this.$store.dispatch("xmlfeedModule/GetCollectionProducts")
            .then((res) => {
                if (res.response.status_code == 2044) {
                   this.collections = res.response.data.collections;
                }
                else{
                    errorModal(res.response.message);
                  }
                unblockSection(section);
            })
            .catch((err) => {
                unblockSection(section);
                errorModal(err.response.message);
            });
        },
        onSelectionChanged(e){
          this.syncSelection(e.component);
        },
        syncSelection(treeView) {
          const selectedCollectionList = treeView.getSelectedNodes()
            .map((node) => node.itemData);

            let arrData = [];
            $.each(selectedCollectionList, function(key, value) {
                if(typeof value.pid !== "undefined"){
                    arrData.push(value);
                }
            });
            
            this.selectedCollectionList = [...arrData];
        },
        generateXML(){
            this.$refs.xmlForm.validate().then(success => {
                if (!success) {
                    $("html, body").animate({ scrollTop: 50 }, 200);
                    return;
                }
                openLoader();
                let formData = {
                    'title': this.XMLFeedName,
                    'defaultxml': this.chooseOptions,
                    'XMLOption': this.XMLOption,
                    'productOption': this.productOption,
                    'selectedProductList': this.selectedProductList,
                    'selectedCollectionList': this.selectedCollectionList,
                };
                this.$store.dispatch("xmlfeedModule/generateXML", formData)
                .then((res) => {
                    if(res.response.status_code == 3003)
                    {
                        successModal(res.response.message);
                        // location.reload();
                    }
                    else{
                        errorModal(res.response.message);
                      }
                    closeLoader();
                })
                .catch((err) => {
                  closeLoader();
                  errorModal(err.response.message);
                });
            });
        },
       setWithSelect(){
         $(".choose").select2();
       },
       handleField(index,status){
        let self = this;
        if(status=='add')
        {
          self.chooseOptions.push({'choose1':10,'choose2':74});
            index++;
          setTimeout(function(){
            self.setWithSelect2(index);
          },500);
        }
        else
        {
           openLoader();
            $(".choose").select2("destroy");
            self.chooseOptions.splice(index,1);
            setTimeout(function(){ 
                $(".choose").select2();
                closeLoader();
            },500);
        }
        this.displayIcon();
       },
       setWithSelect2(index)
       {
          $("#firstchoose"+index).select2();
          $("#secondchoose"+index).select2();
          let self = this;
          $('.selecthandlefirst').on('select2:selecting', function(e) {
            let index = $(this).data('index');
              self.chooseOptions[index].choose1 = parseInt(e.params.args.data.id);
            });

            $('.selecthandlesecond').on('select2:selecting', function(e) {
               let index = $(this).data('index');
               self.chooseOptions[index].choose2 = parseInt(e.params.args.data.id);
            });
       },
       displayIcon(){
        setTimeout(function(){feather.replace()});
      },

    }
  }
</script>

<style lang="scss" scoped>
.store-content p {
    font-size: 13px;
    text-align: justify;
}
.store-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
}
#browse-product-list{
    max-height: 700px;
    overflow: hidden;
    overflow-y: scroll;
}
.feather-30{
width: 30px;
height: 30px;
}
</style>