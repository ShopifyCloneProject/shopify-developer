<template>
  <div>

   <!--  <div class="row">
      <div class="col-12">
          <div class="form-group text-right" >
            <button class="btn btn-primary waves-effect" type="buttom" >Add section</button>
          </div>
      </div>
    </div> -->

     <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Set Default XML field</h4>
          </div>
          <hr>
          <div class="card-content">
            <div class="card-body">
             
              <form>
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
                   <a href="javascript:void(0)" @click.prevent="handleField(index,'add')"  > 
                    <span  v-if="(chooseOptions.length - 1) == index"><i class="feather-30" data-feather='plus-circle'></i></span>
                   </a>
                 </div>
                  <div class="col-1" >
                    <a href="javascript:void(0)" @click.prevent="handleField(index,'remove')" v-if="chooseOptions.length > 1">
                      <span><i class="feather-30" data-feather='minus-circle'></i></span>
                    </a>
                </div>
              </div>
            </form>

            </div>
          </div>
        </div>
      </div>
    </div>

      <div class="row">
        <div class="col-12">
          <div class="form-group text-right" >
            <button class="btn btn-primary waves-effect" type="buttom" @click.prevent="saveDefaultXML">{{ lang.global.save }}</button>
          </div>
        </div>
      </div>

  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['data'],
    name:'defaultxml',
    data() {
      return {
        chooseOptions:[],
        optionGroups1: [],
        optionGroups2: [],
      }
    },
    computed: {
        noImage(){
            return '/assets/images/no-image.jpg';
        },
    },
    mounted(){
        let self = this;
        self.chooseOptions = self.data.objXmlFeed;
        self.optionGroups1 = self.data.objSection1;
        self.optionGroups2 = self.data.objSection2;
        setTimeout(function(){ self.setWithSelect(); },500)        
    },
    methods: {
        saveDefaultXML(){
                openLoader();
                let formData = {
                    'defaultxml': this.chooseOptions
                };
                this.$store.dispatch("xmlfeedModule/saveDefaultXML", formData)
                .then((res) => {
                    closeLoader();
                    if(res.response.status_code == 3000)
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
.feather-30{
width: 30px;
height: 30px;
}
</style>