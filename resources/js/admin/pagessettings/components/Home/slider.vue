<template>
  <div>
    
    <div class="row" id="slidersectionstart">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Slider section</h4>
          <div class="heading-elements">
            <ul class="list-inline mb-0">
               <li class="movesection" @mouseover="setDraggable(true)" @mouseleave="setDraggable(false)"><a><i data-feather="move"></i></a></li>
              <li>
                <a data-action="collapse" class="rotate"><i data-feather="chevron-down"></i></a><!-- class="rotate" -->
              </li>
            </ul>
          </div>
        </div>
        <div class="card-content collapse">
          <div class="card-body" >
            <div class="row">
              <div class="col-sm-12">
                <div class="demo-inline-spacing layout-sec mb-2">
                    <h5 class="mr-1">Visible on page:</h5>
                    <div class="custom-control custom-control-primary custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="slider" v-model="list.status"  />
                        <label class="custom-control-label" for="slider"></label>
                    </div>
                  </div>
              </div>
            </div>

            <div class="row mt-1">
              <div class="col-sm-2">
                  <h5>Align</h5>
              </div>
              <div class="col-sm-10">
                  <div class="demo-inline-spacing mt-0">
                    <div class="custom-control custom-radio mt-0">
                      <input type="radio" id="leftside" name="customRadio" class="custom-control-input" value="0"  v-model.number="formData.align">
                      <label class="custom-control-label" for="leftside">Left</label>
                    </div>
                    <div class="custom-control custom-radio mt-0">
                      <input type="radio" id="rightside" name="customRadio" class="custom-control-input" value="1"  v-model.number="formData.align">
                      <label class="custom-control-label" for="rightside">Right</label>
                    </div>
                  </div>
              </div>
            </div>
            <div class="row mt-1">
              <div class="col-sm-2">
                  <h5>Text1</h5>
              </div>
              <div class="col-sm-10">
                   <input type="text" class="form-control"  v-model="formData.text1"  placeholder="Text 1" />
              </div>
            </div>
            <div class="row mt-1">
              <div class="col-sm-2">
                  <h5>Text2</h5>
              </div>
              <div class="col-sm-10">
                   <input type="text" class="form-control"  v-model="formData.text2"  placeholder="Text 2" />
              </div>
            </div>
            <div class="row mt-1">
              <div class="col-sm-2">
                  <h5>URL</h5>
              </div>
              <div class="col-sm-10">
                   <input type="text" class="form-control"  v-model="formData.url"  placeholder="URL" />
              </div>
            </div>
            <div class="row mt-1">
              <div class="col-sm-2">
                  <h5>Slider</h5>
              </div>
              <div class="col-sm-10" id="slidersection">
                    <section>
                            <div class="draggable-outer-line" v-if="formData.imagedata.length == 0"> 
                                <div class="row no-item"  @click.prevent="callFileData">
                                   <div class="col-md-12 icon">
                                      <i class="feather-32 grey" data-feather='upload-cloud'></i>
                                    </div>
                                    <div class="col-md-12 text">
                                       <div class="add-file"> Add Slider </div>
                                    </div> 
                                </div>   
                            </div> 
                              <div class="item" v-else>
                                <div @mouseover="mouseOverDiv()" @mouseleave="mouseLeaveDiv()">
                                  <div class="delete-outer pointer" v-show="displayDelete" @click="deleteLogo">
                                      <i class="feather-32 white" data-feather='trash-2'></i>
                                  </div>
                                  <div class="image-outer" :class="(displayDelete)?'opacitityDown':''">
                                      <img  class="main-image" :src="formData.imagedata[0].imageurl"  width="100%" height="250px"  alt="">
                                  </div>
                                </div>
                              </div>
                                    
                        </section>
                        
                        <template>
                        <form ref="slidermediaform">
                        <input type="file" name="slidermedia" accept="image/jpeg, image/png" id="slidermedia" class="opacity-0" @change="passSliderImagedata($event)">
                        </form>
                        </template>
              </div>
            </div>
             <div class="row mt-1">
              <div class="col-12 text-center">
                <button class="btn btn-primary waves-effect" type="buttom" @click.prevent="tempSaveData">{{ lang.global.add }}</button>
                <button class="btn btn-outline-primary waves-effect" id="menuReset" type="reset" @click.prevent="resetData">{{ lang.global.reset }}</button>
              </div>
            </div>

             <div class="row mt-2" v-if="sliderData.length > 0">
                <div class="col-12">
                  <div class="responsive-div-table table">
                        <div class="row header">
                          <div class="cell w-15">Align</div>
                          <div class="cell w-20">Text 1</div>
                          <div class="cell w-20">Text 2</div>
                          <div class="cell w-25">URL</div>
                          <div class="cell w-10">Image</div>
                          <div class="cell w-10">Action</div>
                        </div>
                  </div>
                  <draggable class="responsive-div-table" v-model="sliderData" :disabled="!dragdisabled" @end="endDrag"  draggable=".menuitem">
                    <div class="menuitem row w-100 pl-1" v-for="(sliderdata, index) in sliderData" >
                      <div class="cell w-15  p-1"><a href="javascript:void(0)" @mouseover="setDraggableMenu(true)" @mouseleave="setDraggableMenu(false)"><i data-feather='move'></i></a> {{ (sliderdata.align == '0')?'Left':'Right' }}</div>
                      <div class="cell w-20  p-1">{{ sliderdata.text1 }}</div>
                      <div class="cell w-20  p-1">{{ sliderdata.text2 }}</div>
                      <div class="cell w-25  p-1">{{ sliderdata.url }}</div>
                      <div class="cell w-10  p-1">  
                        <img  class="main-image" :src="sliderdata.imagedata[0].imageurl"  width="100%" height="50px"  alt=""  @error="setAltImg">
                      </div>
                      <div class="cell w-10 p-1 text-center">
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

  </div>
</template>

<script>
import draggable from 'vuedraggable'


  export default {
    props: ['list'],
    name:'slider',
    data() {
      return {
        formData: {
            align: 0,
            text1: null,
            text2: null,
            url: null,
            imagedata: [],
          },
          displayDelete: false,
          dragdisabled: false,
          sliderData: [],
        }
    },
    components: {
        draggable: draggable,
    },
    created() {
    },
    mounted() {
        this.sliderData = this.list.pagemedias;
        
    },
    methods: {
      tempSaveData(){
        if(this.formData.imagedata.length == 0)
        {
           errorModal('Please select slider image');
           return ;
        }

        blockSection($("#slidersectionstart"));
        this.sliderData.push(this.formData);
        this.resetData();
        unblockSection($("#slidersectionstart"));
      },
      resetData(){
        this.formData = {
            align: 0,
            text1: null,
            text2: null,
            url: null,
            imagedata: [],
          };
        setTimeout(function(){ feather.replace(); }, 500);
      },
      setWithParent(){
        this.list.pagemedias = this.sliderData;
      },
      removeMenu(index)
      {
        this.sliderData.splice(index,1);
        this.setWithParent();
      },
       callFileData(){
            $("#slidermedia").trigger('click');
        },
        passSliderImagedata(event){
            blockSection($("#slidersection"));
            let self = this;
                const  reader = new FileReader();
                 reader.readAsDataURL(event.target.files[0]); 
                 reader.onload  = function() {
                        self.appendMedia(reader.result);
                }
            self.$refs.slidermediaform.reset();
            unblockSection($("#slidersection"));
        },
        appendMedia(base64image){
            this.formData.imagedata.push({id: 'new',  imageurl: base64image });
        },
         mouseOverDiv(){
            this.displayDelete = true;
            feather.replace();
        },
        mouseLeaveDiv(){
            this.displayDelete = false;
        },
        deleteLogo(){
          this.formData.imagedata = [];
          setTimeout(function(){ feather.replace(); }, 500);
        },
        setAltImg(event){
         event.target.src = '/assets/images/no-image.jpg';
        },
        setDraggableMenu(status){
         this.dragdisabled = status;
        },
        endDrag(){
         feather.replace();
         this.setWithParent();
      },
       setDraggable(status){
        this.$emit('setDraggable', status);
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

.item{
  position: relative;
  .delete-outer{
    position: absolute;
    right: 10px;
    top: 10px;
    z-index: 9999;
  }
  .image-outer{
    .main-image{
      border: 2px dashed #2c1ec9;
      border-radius: 15px;
    }
  }
}

.feather-32{
width: 32px;
height: 32px;
}
.white{
  color: #fff;
}
.grey{
  color: grey;
}
</style>


