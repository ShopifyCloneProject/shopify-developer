<template>
  <div>

    <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Header section</h4>
          <div class="heading-elements">
            <ul class="list-inline mb-0">
              <li class="movesection" @mouseover="setDraggable(true)" @mouseleave="setDraggable(false)"><a><i data-feather="move"></i></a></li>
              <li>
                <a data-action="collapse" class="rotate"><i data-feather="chevron-down"></i></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-content collapse">
          <div class="card-body">
            <div class="row" id="headerData">
              <div class="col-sm-12">
                <div class="demo-inline-spacing layout-sec mb-2">
                    <h5 class="mr-1">Visible on page:</h5>
                    <div class="custom-control custom-control-primary custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="headerDisplay" v-model="list.status"  />
                        <label class="custom-control-label" for="headerDisplay"></label>
                    </div>
                </div>
              </div>
            </div>
               <div class="row mt-3">
                <div class="col-md-6 col-12"><h4>Logo</h4>
                    <div class="row">
                        <div class="col-12">
                        <section>
                            <div class="draggable-outer-line" v-if="list.logo.length == 0"> 
                                <div class="row no-item"  @click.prevent="callFileData">
                                   <div class="col-md-12 icon">
                                        <img width="40" src="data:image/svg+xml,%3csvg fill='none' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill-rule='evenodd' clip-rule='evenodd' d='M20 10a10 10 0 11-20 0 10 10 0 0120 0zM5.3 8.3l4-4a1 1 0 011.4 0l4 4a1 1 0 01-1.4 1.4L11 7.4V15a1 1 0 11-2 0V7.4L6.7 9.7a1 1 0 01-1.4-1.4z' fill='%235C5F62'/%3e%3c/svg%3e"  alt="" />
                                    </div>
                                    <div class="col-md-12 text">
                                       <div class="add-file"> Add Logo </div>
                                    </div> 
                                </div>   
                            </div> 
                            <div class="DropZone__Container hasitem" v-else>
                                    <div class="dropzone-first">
                                        <div class="dropzone-second-div">
                                        <div class="item">
                                            <button class="btnImage" type="button" @mouseover="mouseOverDiv()" @mouseleave="mouseLeaveDiv()">
                                                <div class="checkbox-outer" v-show="displayDelete" @click="deleteLogo">
                                                    <i data-feather='trash-2'></i>
                                                </div>
                                                <div class="image-outer" :class="(displayDelete)?'opacitityDown':''">
                                                    <img img class="main-image" :src="list.logo[0].imageurl"  alt="">
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        
                        <template>
                            <form ref="mediaform">
                                <input type="file" name="media" multiple="" accept="image/jpeg, image/png" id="media" class="opacity-0" @change="passImageData($event)">
                            </form>
                        </template>

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12"><h4>Icon</h4>
                    <div class="row">
                        <div class="col-12">
                        <section>
                            <div class="draggable-outer-line" v-if="list.icon.length == 0"> 
                                <div class="row no-item"  @click.prevent="callIconData">
                                   <div class="col-md-12 icon">
                                        <img width="40" src="data:image/svg+xml,%3csvg fill='none' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill-rule='evenodd' clip-rule='evenodd' d='M20 10a10 10 0 11-20 0 10 10 0 0120 0zM5.3 8.3l4-4a1 1 0 011.4 0l4 4a1 1 0 01-1.4 1.4L11 7.4V15a1 1 0 11-2 0V7.4L6.7 9.7a1 1 0 01-1.4-1.4z' fill='%235C5F62'/%3e%3c/svg%3e"  alt="" />
                                    </div>
                                    <div class="col-md-12 text">
                                       <div class="add-file"> Add Icon </div>
                                    </div> 
                                </div>   
                            </div> 
                            <div class="DropZone__Container hasitem" v-else>
                                    <div class="dropzone-first">
                                        <div class="dropzone-second-div">
                                        <div class="item">
                                            <button class="btnImage" type="button" @mouseover="mouseOverDiv()" @mouseleave="mouseLeaveDiv()">
                                                <div class="checkbox-outer" v-show="displayDelete" @click="deleteIcon">
                                                    <i data-feather='trash-2'></i>
                                                </div>
                                                <div class="image-outer" :class="(displayDelete)?'opacitityDown':''">
                                                    <img img class="main-image" :src="list.icon[0].imageurl"  alt="">
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        
                        <template>
                            <form ref="iconform">
                                <input type="file" name="icon_media" multiple="" accept="image/jpeg, image/png" id="icon_media" class="opacity-0" @change="passIconData($event)">
                            </form>
                        </template>

                        </div>
                    </div>
                </div>
               </div>
               <div class="row mt-1">
                    <div class="col-sm-2">
                        <h5>Title :</h5>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"  v-model="list.title"  placeholder="Title" />
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
</template>
<script>
  export default {
    props: ['list','objCollections','objProducts', 'menudata'],
    name:'headersection',
    data() {
      return {
        displayDelete: false,
        formData :{
          menuname: null,
          setlink: 'directurl',
          category: '',
          categoryrelation: [],
          url: null,
          title : '',
        },
        isLoading: false,
        productData: [],
        dragdisabled: false,
        }
    },
    components: {
    },
    created() {
    },
    mounted() {
      feather.replace();
      this.productData = this.objProducts;
    },
    methods: {
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
      callFileData(){
            $("#media").trigger('click');
        },
        passImageData(event){
            blockSection($("#headerData"));
            let self = this;
                const  reader = new FileReader();
                 reader.readAsDataURL(event.target.files[0]); 
                 reader.onload  = function() {
                        self.appendMedia(reader.result);
                }
            self.$refs.mediaform.reset();
            unblockSection($("#headerData"));
        },
        appendMedia(base64image){
            this.list.logo.push({id: 'new',  imageurl: base64image });
        },
        mouseOverDiv(){
            feather.replace();
            this.displayDelete = true;
        },
        mouseLeaveDiv(){
            this.displayDelete = false;
        },
        deleteLogo(){
          this.list.logo = [];
        },

        FilterTitleName(option) {
        return `${option.title}`;
      },
      setDraggable(status){
        this.$emit('setDraggable', status);
      },
       passIconData(event){
            blockSection($("#headerData"));
            let self = this;
                const  reader = new FileReader();
                 reader.readAsDataURL(event.target.files[0]); 
                 reader.onload  = function() {
                        self.appendIcon(reader.result);
                }
            self.$refs.iconform.reset();
            unblockSection($("#headerData"));
        },
        appendIcon(base64image){
            this.list.icon.push({id: 'new',  imageurl : base64image });
        },
       callIconData(){
            $("#icon_media").trigger('click');
        },
         deleteIcon(){
          this.list.icon = [];
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


