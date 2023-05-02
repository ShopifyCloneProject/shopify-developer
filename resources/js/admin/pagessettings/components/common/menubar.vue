<template>
  <div>
    <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Menu section</h4>
          <div class="heading-elements">
            <ul class="list-inline mb-0">
                <a data-action="collapse"><i data-feather="chevron-down"></i></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-content collapse show">
          <div class="card-body">
              <div class="row mt-3">
                <div class="col-md-12 col-12">
                   <ValidationObserver ref="productForm" v-slot="{ handleSubmit,  invalid, reset }">
                    <form @submit.prevent="handleSubmit(saveMenuData())" @reset.prevent="resetData">
                      <ValidationProvider  name="Menu name" rules="required" v-slot="{ errors }">
                      <div class="row">
                        <div class="col-md-3">Menu name</div>
                        <div class="col-md-8">
                          <input type="text" class="form-control"  v-model="formData.menuname"  placeholder="Menu name" />
                           <p class="error text-danger">{{ errors[0] }}</p>
                        </div>
                      </div>
                      </ValidationProvider>

                       <div class="row mt-1">
                        <div class="col-md-3">Set Link</div>
                        <div class="col-md-9">
                          <div class="demo-inline-spacing mt-0">
                            <div class="custom-control custom-radio mt-0">
                              <input type="radio" id="directurl" name="setlink" class="custom-control-input" value="directurl"  @change="selectSetlink()"  v-model="formData.setlink">
                              <label class="custom-control-label" for="directurl">Direct Url</label>
                            </div>
                            <div class="custom-control custom-radio mt-0">
                              <input type="radio" id="childrenmenu" name="setlink" class="custom-control-input" value="makechild" @change="selectSetlink()"  v-model="formData.setlink">
                              <label class="custom-control-label" for="childrenmenu">Children menu</label>
                            </div>
                            <div class="custom-control custom-radio mt-0">
                              <input type="radio" id="chooseOption" name="setlink" class="custom-control-input" value="chooseoption" @change="selectSetlink()"  v-model="formData.setlink">
                              <label class="custom-control-label" for="chooseOption">Product or Collection</label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <ValidationProvider  name="URL" rules="required" v-slot="{ errors }" v-if="formData.setlink == 'directurl'">
                      <div class="row mt-1" v-if="formData.setlink == 'directurl'">
                        <div class="col-md-3">Url</div>
                        <div class="col-md-8">
                          <input type="text" class="form-control"  v-model="formData.url"  placeholder="Url" />
                           <p class="error text-danger">{{ errors[0] }}</p>
                        </div>
                      </div>
                      </ValidationProvider>

                      <div class="row mt-1" v-if="formData.setlink=='chooseoption'">
                        <div class="col-md-3">Select Category</div>
                        <div class="col-md-8">
                          <div class="demo-inline-spacing mt-0">
                            <div class="custom-control custom-radio mt-0">
                              <input type="radio" id="choosecollection" name="customRadio" class="custom-control-input" value="collection"  v-model="formData.category" @change="changeCategory()">
                              <label class="custom-control-label" for="choosecollection">Collection</label>
                            </div>
                            <div class="custom-control custom-radio mt-0">
                              <input type="radio" id="chooseProduct" name="customRadio" class="custom-control-input" value="product"  v-model="formData.category" @change="changeCategory()">
                              <label class="custom-control-label" for="chooseProduct">Product</label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="row mt-1" v-if="formData.category=='collection'" >
                        <div class="col-md-3">Collection</div>
                        <div class="col-md-8">
                          <multiselect
                                      v-model="formData.categoryrelation"
                                      :options="objCollections"
                                      :taggable="false"
                                      tag-placeholder="Add this collection"
                                      placeholder="search or add collection"
                                      :custom-label="FilterTitleName"
                                      track-by="id"
                                      :close-on-select="true"
                                      :clear-on-select="true"
                                      >
                          </multiselect>
                        </div>
                      </div>
                      <div class="row mt-1" v-else-if="formData.category=='product'">
                        <div class="col-md-3">Product</div>
                        <div class="col-md-8">
                          <multiselect
                                      v-model="formData.categoryrelation"
                                      :options="productData"
                                      :taggable="false"
                                      tag-placeholder="Add this product"
                                      placeholder="search or add product"
                                      :custom-label="FilterTitleName"
                                      track-by="id"
                                      :close-on-select="true"
                                      :clear-on-select="true"
                                      @search-change="asyncFind"
                                      :loading="isLoading"
                                      >
                          </multiselect>
                        </div>
                      </div>

                      <div class="row mt-2">
                        <div class="col-12">
                          <button class="btn btn-primary waves-effect">{{ lang.global.add }}</button>
                          <button class="btn btn-outline-primary waves-effect" id="menuReset" type="reset">{{ lang.global.reset }}</button>
                        </div>
                      </div>
                    </form>
                  </ValidationObserver>
                </div>
              </div>

              
              <div class="row mt-3" v-if="menuData.length > 0">
                      <div class="col-12">
                        <div class="responsive-div-table table">
                              <div class="row header">
                                <div class="cell w-25">Menu Name</div>
                                <div class="cell w-20">Select category</div>
                                <div class="cell w-45">Link</div>
                                <div class="cell w-10">Action</div>
                              </div>
                        </div>
                        <draggable class="responsive-div-table" v-model="menuData" :disabled="!dragdisabled" @end="endDrag"  draggable=".menuitem">
                          <div class="menuitem row w-100 pl-1" v-for="(menudata, index) in menuData" >
                            <div class="cell w-25  p-1"><a href="javascript:void(0)" @mouseover="setDraggableMenu(true)" @mouseleave="setDraggableMenu(false)"><i data-feather='move'></i></a hrfe="javascript:void(0)"> {{ menudata.menuname }}</div>
                            <div class="cell w-20  p-1">{{ (menudata.setlink == 'directurl')?'URL':(menudata.setlink == 'chooseoption')?(menudata.category == 'collection')?'Colletion':'Product':'Children' }}</div>
                            <div class="cell w-45  p-1">{{ (menudata.setlink == 'directurl')?menudata.url:(menudata.setlink == 'chooseoption')?menudata.categoryrelation.title: '-' }}</div>
                            <div class="cell w-10 p-1 text-center">
                              <a :href="'/admin/page/home/2/'+menudata.id" v-if="menudata.setlink == 'makechild' && menudata.id > 0"><i data-feather='corner-down-right'></i></a>
                              <a href="javascript:void(0)" @click.prevent="removeMenu(index)"><i data-feather='trash-2'></i></a>
                            </div>
                          </div>
                        </draggable>  
                      </div>
                </div>
          </div>
        </div>


      </div>
    </div>
  </div>

        <div class="form-group text-left mt-2" >
          <button class="btn btn-primary waves-effect fixed-right-bottom" @click.prevent="saveMenu">{{ lang.global.save }}</button>
        </div>
</div>
</template>
<script>
  export default {
    props: ['list'],
    name:'menubar',
    data() {
      return {
        displayDelete: false,
        formData :{
          menuname: null,
          setlink: 'directurl',
          category: '',
          categoryrelation: [],
          url: null
        },
        isLoading: false,
        productData: [],
        objCollections: [],
        menuData: [],
        dragdisabled: false,
        }
    },
    components: {
    },
    created() {
    },
    mounted() {
      feather.replace();
     this.productData = this.list.objProducts;
      this.objCollections = this.list.objCollections;
      this.menuData = this.list.menudata;
    },
    methods: {
      saveMenu(){
            openLoader();
            let formData = {
                'menuData': this.menuData
            };
            this.$store.dispatch("pageModule/saveMenu", formData)
            .then((res) => {
                closeLoader();
                if(res.response.status_code == 3005)
                {
                    successModal(res.response.message);
                    setTimeout(function(){
                      location.reload();
                    },2000);
                }
            })
            .catch((err) => {
              closeLoader();
              errorModal(err.response.message);
            });
        },
      saveMenuData(){
         this.$refs.productForm.validate().then(success => {
                if (!success) {
                  return;
                }
                this.menuData.push(this.formData);
                setTimeout(function(){ feather.replace(); }, 500); 
                this.resetData();
              });
      },
      resetData(){
        this.formData = {
          menuname: null,
          setlink: 'directurl',
          category: '',
          categoryrelation: '',
          url: null
        };
        $(".error").remove();
      },
       changeCategory(){
        this.formData.categoryrelation = [];
      },
      selectSetlink(){
        if(this.formData.setlink == 'directurl')
        {
            this.formData.category = '';
        }
        else if(this.formData.setlink == 'chooseoption')
        {
          if(this.formData.category == '')
          {
            this.formData.category = 'collection';
          }
        }
      },
      asyncFind(query){
        this.isLoading = true;
         this.$store.dispatch("pageModule/getSearchProduct", {'search': query})
            .then((res) => {
                 this.isLoading = false;
                if(res.response.status_code == 2044)
                {
                    this.productData = res.response.data;
                }
            })
            .catch((err) => {
              this.isLoading = false;
              errorModal(err.response.message);
            });
       
      },
      removeMenu(index)
      {
        this.menuData.splice(index,1);
      },
        FilterTitleName(option) {
        return `${option.title}`;
      },
      endDrag()
      {
         feather.replace();
      },
      setDraggableMenu(status){
        this.dragdisabled = status;
      },
    },
    computed: {
    },
  }
</script>

<style lang="scss" scoped>
.opacitityDown{
    background-color: #969393;
    opacity: 0.6;
}

.draggable-outer-line{
    width: 100%;
    border: 2px dashed #9f9f9f;
    border-radius: 15px;
    &:hover{
        background-color: #e3e2e2;
        cursor: pointer;
    }
    .no-item{
        margin: 5%;
        min-height: 100px;
        .icon{
            text-align: center;
            margin-bottom: 10px;
        }
        .text{
            text-align: center;
            .add-file{
                display: inline-block;
                padding: 10px 15px;
                border: 2px solid #c1c1c1;
                border-radius: 5px;
                font-weight: 600;
            }
        }
    }

}

 .hasitem{
        min-height: 100px;
        position: relative;
        flex: 1 1;
        .dropzone-first {
            position: relative;
            height: 100%;
        }
        .dropzone-second-div {
            display: grid;
            grid-gap: .8rem;
            -webkit-user-select: none;
            user-select: none;
            grid-template-columns: repeat(4,1fr);
            .btnImage {
                width: 100%;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                border: .1rem solid #c9cccf;
                background: #ffffff;
                border-radius: 6px;
                padding: 0;
                margin: 0;
                .checkbox-outer{
                    position: absolute;
                    z-index: 99;
                    top: 2%;
                    left: 5%;
                }
                .image-outer{
                            width: 100%;
                            display: flex;
                            overflow: hidden;
                            flex-direction: column;
                            z-index: 5;
                            align-items: center;
                            justify-content: center;
                            border-radius: 5px;
                            transform: translateZ(0);
                            &:after{
                                content: "";
                                display: block;
                                width: 100%;
                                padding-bottom: 100%;
                            }
                            img {
                                position: absolute;
                                z-index: 1;
                                max-width: 100%;
                                max-height: 100%;
                            }
                }   
            }
        }
    }


</style>


