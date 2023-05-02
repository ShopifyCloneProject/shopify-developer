<template>
  <div>

     <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">{{ list.parentmenuname }} ({{ list.level }} Menubar level) </h4>
            <div class="heading-elements">
              <ul class="list-inline mb-0">
                <li>
                  <a data-action="collapse"><i data-feather="chevron-down"></i></a>
                </li>
              </ul>
            </div>
          </div>
          <div class="card-content collapse show">
            <div class="card-body">
              

               <div class="row mt-3">
                <div class="col-md-3 col-12"><h4>Menu</h4></div>
                <div class="col-md-9 col-12">
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
                        <div class="col-md-9">
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
                          <button class="btn btn-primary waves-effect" type="buttom">{{ lang.global.add }}</button>
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
                              <a :href="'/admin/page/home/'+finalAfterLevel+'/'+menudata.id" v-if="menudata.setlink == 'makechild' && menudata.id > 0"><i data-feather='corner-down-right'></i></a>
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

    <div class="form-group text-right">
        <button class="btn btn-primary waves-effect" type="buttom" @click.prevent="saveLevelPage">{{ lang.global.save }}</button>
    </div>
  </div>
</template>

<script>

  export default {
     props: ['list'],
    name:'levelpage',
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
        menuData: [],
        finalAfterLevel: 2,
        objCollections: [],
        dragdisabled: false,
        }
    },
    created() {
    },
    mounted() {
      feather.replace();
      this.productData = this.list.objProducts;
      this.objCollections = this.list.objCollections;
      this.menuData = this.list.menudata;
      this.finalAfterLevel = parseInt(this.list.level) + 1;
    },
   methods: {
     saveLevelPage(){
                openLoader();
                let formData = {
                    'levelData': this.menuData,
                    'level': this.list.level,
                    'parentid': this.list.parentid, 
                };
                this.$store.dispatch("pageModule/saveLevelPage", formData)
                .then((res) => {
                    closeLoader();
                    if(res.response.status_code == 2094)
                    {
                        successModal(res.response.message);
                    }
                    location.reload();
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
      endDrag()
      {
         feather.replace();
         this.setWithParent();
      },
      setDraggableMenu(status){
        this.dragdisabled = status;
      },
      FilterTitleName(option) {
        return `${option.title}`;
      },
    },
    
  }
</script>

<style lang="scss" scoped>

</style>


