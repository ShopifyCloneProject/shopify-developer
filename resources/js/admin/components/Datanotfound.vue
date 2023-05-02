<template>
  <div class="row" >
          <div class="col-md-12 col-12">
            <div class="card"> 
              <div class="card-header align-self-center">
                <h3>{{ lang.global.data_not_found }}</h3>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <button class="btn btn-danger waves-effect waves-float waves-light" type="button" @click="backtolist()">
                  {{ lang.global.back_to_list }}
              </button>
          </div>
          </div>
        </div>
</template>

<script>
  export default {
    name:'datanotfound',
    methods:{
      backtolist(){
        window.history.back();
      },
    }
  }

</script>