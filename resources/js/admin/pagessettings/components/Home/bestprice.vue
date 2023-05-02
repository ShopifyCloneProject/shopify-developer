<template>
  <div>
      <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Best price section</h4>
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
            <div class="row">
              <div class="col-sm-12">
                 <div class="demo-inline-spacing layout-sec mb-2">
                      <h5 class="mr-1">Visible on page:</h5>
                      <div class="custom-control custom-control-primary custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="bestprice" v-model="list.status"  />
                          <label class="custom-control-label" for="bestprice"></label>
                      </div>
                  </div>
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
    props: ['list'],
    name:'bestprice',
    data() {
      return {
        }
    },
    components: {
    },
    created() {
    },
    mounted() {
        
        
    },
    methods: {
       setDraggable(status){
        this.$emit('setDraggable', status);
      },
    },
    computed: {
    },
  }
</script>

<style lang="scss" scoped>

</style>
