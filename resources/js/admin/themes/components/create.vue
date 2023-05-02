<template>
  <div id="input-sizing">
    <ValidationObserver ref="themeForm" v-slot="{ handleSubmit }">
      <form method="POST" enctype="multipart/form-data" id="frmAddEditThemes" @submit.prevent="handleSubmit(submit())">
          <div class="row">
            <div class="col-12 mb-2">
              <div  class="back-url" v-if="type == 'Edit'">
                  <i data-feather='arrow-left-circle'></i> {{ lang.global.edit }}  {{ beforeEdit.name }}
              </div>
              <div  class="back-url" v-else>
                  <i data-feather='arrow-left-circle'></i> {{ lang.cruds.themes.addtheme }}
              </div>
            </div>
            <div class="col-md-8 col-12">
                  <!-- Basic details start -->
                  <div class="card">
                      <div class="card-body">
                         <div class="row">
                            <div class="col-12">
                                <ValidationProvider  name="Name" rules="required" v-slot="{ errors }">
                                    <div class="form-group">
                                        <label class="required" for="store_theme">{{ lang.cruds.themes.fields.name }}</label>
                                        <input class="form-control" type="text" v-model="formData.name">
                                        <p class="text-danger">{{ errors[0] }}</p>
                                    </div>
                                </ValidationProvider>
                            </div>
                         </div>
                      </div>
                  </div>
                  <div class="card" id="mediaDataId">
                      <div class="card-header">
                         <h4 class="card-title">{{ lang.cruds.themes.fields.image }}</h4>
                         <button v-if="showDeleteBtn" @click.prevent="deleteMedia" type="button" class="btn btn-outline-warning waves-effect pull-right">{{ lang.global.deleteselectmedia }}</button>
                      </div>
                      <div class="card-body">
                        <section>
                            <div class="draggable-outer-line" v-if="images.length == 0"> 
                                <div class="row no-item"  @click.prevent="callFileData">
                                   <div class="col-md-12 icon">
                                        <img width="40" src="data:image/svg+xml,%3csvg fill='none' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill-rule='evenodd' clip-rule='evenodd' d='M20 10a10 10 0 11-20 0 10 10 0 0120 0zM5.3 8.3l4-4a1 1 0 011.4 0l4 4a1 1 0 01-1.4 1.4L11 7.4V15a1 1 0 11-2 0V7.4L6.7 9.7a1 1 0 01-1.4-1.4z' fill='%235C5F62'/%3e%3c/svg%3e"  alt="" />
                                    </div>
                                    <div class="col-md-12 text">
                                       <div class="add-file"> {{ lang.global.add_media }} </div>
                                    </div> 
                                </div>   
                            </div> 
                            <div class="DropZone__Container hasitem" v-else>
                                    <div class="dropzone-first">
                                        <draggable tag="div" v-model="images" class="dropzone-second-div" draggable=".item">
                                        <div v-for="(imagedata,index) in images" class="item">
                                            <button class="btnImage" type="button" @mouseover="mouseOverDiv(index)" @mouseleave="mouseLeaveDiv(index)">
                                                <div class="checkbox-outer" v-show="imagedata.displaycheckbox">
                                                    <div class="custom-control custom-checkbox">
                                                      <input type="checkbox" class="custom-control-input" :id="'deletecheckbox'+imagedata.id" v-model="imagedata.checked" @change="checkAnySelect">
                                                      <label class="custom-control-label" :for="'deletecheckbox'+imagedata.id"></label>
                                                    </div>
                                                </div>
                                                <div class="image-outer" :class="(imagedata.displaycheckbox)?'opacitityDown':''">
                                                    <img class="main-image" :src="imagedata.imageurl"  alt="">
                                                </div>
                                            </button>
                                        </div>
                                        <div class="add-media-outer">
                                            <button class="add-image" type="button" @click.prevent="callFileData">
                                                    <span class="add-image-link">{{ lang.global.add_media }}</span>
                                            </button>
                                        </div>
                                    </draggable>
                                </div>
                            </div>
                        </section>
                        
                        <template>
                        <form ref="mediaform">
                        <input type="file" name="images[]" multiple="" accept="image/jpeg, image/png" id="media" class="opacity-0" @change="passImageData($event)">
                        </form>
                        </template>
                        
                        <!-- multi file upload ends -->
                      </div>
                  </div>
                  <div class="card">
                        <div class="card-body">
                            <div class="row">
                              <div class="col-12">
                                  <div class="form-group">
                                      <label class="required" for="themeurl">{{ lang.cruds.themes.fields.themeurl }}</label>
                                      <input class="form-control" type="text" v-model="formData.themeurl">
                                  </div>
                              </div>
                            </div>
                        </div>
                    </div>  
                  <!-- Basic details end -->
            </div>
          </div>
          <div class="form-group float-left">
              <button class="btn btn-primary waves-effect waves-light" type="submit">
                  {{ lang.global.save }}
              </button>
              <button class="btn btn-danger waves-effect waves-light" type="button" @click="cancel()" >
                  {{ lang.global.cancel }}
              </button>
          </div>
          <br>
      </form>
    </ValidationObserver>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import draggable from 'vuedraggable'

export default {
    props: ['list', 'data', 'type'],
    name:'AddTheme',
    data() {
      return {
        formData:{
          name:'',
          themeurl:''
        },
        showDeleteBtn: false,
        images: [],
        client_id: CLIENT_ID,
        beforeEdit:{}
      }
    },
    mounted(){
        if(this.type == 'Edit'){
        this.setFormData();
      }
      else
      {
        this.beforeEdit = this.formData;
      }
    },
    computed: {
    },
    created() {
      
    },
    methods: {
      callFileData(){
            $("#media").trigger('click');
      },
      passImageData(event){
            blockSection($("#mediaDataId"));
            let self = this;
            for (const [key, value] of Object.entries(event.target.files)) {
                const  reader = new FileReader();
                 reader.readAsDataURL(value); 
                 reader.onload  = function() {
                        self.appendMedia(reader.result);
                    }
            }
            self.$refs.mediaform.reset();
            unblockSection($("#mediaDataId"));
      },
      appendMedia(base64image){
            this.images.push({id: Math.floor((Math.random() * -10000000)),  imageurl: base64image, checked: false, displaycheckbox: false, media: [] });
      },
      mouseOverDiv(index){
            this.images[index].displaycheckbox = true;
      },
      mouseLeaveDiv(index){
            this.images[index].displaycheckbox = false;
            this.checkAnySelect();
      },
      checkAnySelect(){
            let funCheckDisplayCheckbox = false;
                for (const [key, value] of Object.entries(this.images)) {
                    if(value.checked)
                    {
                        funCheckDisplayCheckbox = true;
                        this.showDeleteBtn = true;
                        break;
                    }
                }

                if(funCheckDisplayCheckbox)
                {
                    for (const [key, value] of Object.entries(this.images)) {
                            value.displaycheckbox = true;
                    }
                }
                else
                {
                    for (const [key, value] of Object.entries(this.images)) {
                            value.displaycheckbox = false;
                    }
                            this.showDeleteBtn = false;
                }
      },
      deleteMedia(){
             for (const [key, value] of Object.entries(this.images)) {
                            if(value.checked) 
                            {
                                this.deleteMediaIndex();
                            }
            }
            this.showDeleteBtn = false;
            this.checkAnySelect();
      },
      deleteMediaIndex(){
            for (const [key, value] of Object.entries(this.images)) {
                        if(value.checked)
                        {
                            this.images.splice(key, 1);
                            this.deleteMedia();
                        }
                }
      },
      setFormData(){
          let data = this.data;
          this.beforeEdit = data.themes;
          this.formData = data.themes;
          let objImage =[];
          let  tempObjImage= {};
          for (const [key, value] of Object.entries(this.data.images)) {
                  tempObjImage = {
                              checked: false,
                              displaycheckbox: false,
                              id: value.id,
                              imageurl: '/storage/'+CLIENT_ID+'/themes/'+value.theme_id+'/'+value.image
                              };
                objImage.push(tempObjImage);
          }
          this.images = objImage;
      },
      submit(){
                this.$refs.themeForm.validate().then(success => {
                        if (!success) {
                             $("html, body").animate({ scrollTop: 50 }, 200);
                          return;
                        }
                openLoader();
                this.formData.images = this.images;
                if(this.type == 'Add')
                {
                  this.$store.dispatch("themeSelectionModule/AddTheme", this.formData)
                  .then((res) => {
                      if (res.response.status_code == 3009) {
                          successModal(res.response.message);
                          setTimeout(function(){
                            window.location = res.response.data.url;
                         },2000);
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
                }
                else
                {
                  this.formData.id = this.data.themes.id;
                  this.$store.dispatch("themeSelectionModule/EditTheme", this.formData)
                  .then((res) => {
                      if (res.response.status_code == 3010) {
                          successModal(res.response.message);
                          window.location = res.response.data.url;
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
                
                }
            });
        },
        cancel(){
           location.reload();
        },
    },
    
  }
</script>

<style lang="scss" scoped>
.opacity-0{
    opacity: 0;
}
.productVariantCombination{
    tbody{
        tr{
            &:hover{
                background-color: #f8f8f8;
            }
        }
    }
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

    .DropZone__Container{
        min-height: 200px;
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

.Polaris-Choice--labelHidden_14tn9 {
    padding: 0;
}

@media (min-width: 0px) and (max-width: 991.98px)
{
    .dropzone-second-div{
    grid-template-columns: repeat(3,1fr);
    }
}

@media (min-width: 992px)
{
    .dropzone-second-div{
    grid-template-columns: repeat(4,1fr);
    }
}

.dropzone-second-div>:first-child {
    grid-column: 1/span 2;
    grid-row: 1/span 2;
}
.add-image{
    height: 100%;
    border: 0.2rem dashed #c9cccf !important;
    min-height: 150px;
    .image-outer-media{
        position: absolute;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        flex-direction: column;
        text-align: center;
        padding: .8rem;
        cursor: pointer;
        outline: none;
        background: none;
        border: none;
        .add-image-link{
            margin-top: 40%;
        }
    }
}
.opacitityDown{
    background-color: #969393;
    opacity: 0.6;
}

.add-media-outer{
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: calc(100% - .4rem);
    border-radius: 3px;
    .add-image{
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            flex-direction: column;
            text-align: center;
            padding: .8rem;
            cursor: pointer;
            outline: none;
            background: none;
            .add-image-link{
                margin-top: 40%;
            }
    }
}


.back-url{
    font-size: 18px;
    color: #5e5873;
}
.store-content p {
    font-size: 13px;
    text-align: justify;
}
.store-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
}
hr{
  margin-bottom: 3rem;
}

</style>