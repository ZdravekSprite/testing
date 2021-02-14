<template>
  <div class="addDay">
    <input type="date" v-model="day.day" />
    <font-awesome-icon
      icon="plus-square"
      @click="addDay()"
      :class="[day.day ? 'active' : 'inactive', 'plus']"
    />
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      day: {
        day: "",
      },
    };
  },
  methods: {
    addDay() {
      if (this.day.day == "") {
        return;
      }
      axios
        .post("api/day/store", {
          day: this.day,
        })
        .then((response) => {
          if (response.status == 201) {
            this.day.day = "";
            this.$emit("reloadlist");
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style scoped>
.addDay {
  display: flex;
  justify-content: center;
  align-items: center;
}
input {
  background: #f7f7f7;
  border: 0px;
  outline: none;
  padding: 5px;
  margin-right: 10px;
  width: 100%;
}
.plus {
  font-size: 20px;
}
.active {
  color: #00ce25;
}
.inactive {
  color: #999999;
}
</style>