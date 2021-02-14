<template>
  <div class="daysListContainer">
    <div class="heading">
      <h2 id="title">Days List</h2>
      <add-day-form v-on:reloadlist="getList()" />
    </div>
    <list-view :days="days" v-on:reloadlist="getList()" />
  </div>
</template>

<script>
import addDayForm from "./addDayForm";
import listView from "./listView";
export default {
  components: {
    addDayForm,
    listView,
  },
  data: function () {
    return {
      days: [],
    };
  },
  methods: {
    getList() {
      axios
        .get("api/days")
        .then((response) => {
          this.days = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  created() {
    this.getList();
  },
};
</script>

<style scoped>
.daysListContainer {
  width: 350px;
  margin: auto;
}
.heading {
  background: #e6e6e6;
  padding: 10px;
}
#title {
  text-align: center;
}
</style>