<template>
  <div class="day">
    <span :class="[day.sick ? 'sick' : '', 'dayText']">{{ day.day }} </span>
    <day-bar :day="day" />
    <input
      title="bolovanje"
      type="checkbox"
      @change="updateSick()"
      v-model="day.sick"
    /><span>B</span>
    <button title="izbriši" @click="removeDay()" class="trashcan">
      <font-awesome-icon icon="trash" />
    </button>
  </div>
</template>

<script>
import dayBar from "./dayBar";
export default {
  props: ["day"],
  components: {
    dayBar,
  },
  methods: {
    updateSick() {
      //console.log("sick", this.day.sick ? true : false);
      axios
        .put("api/day/" + this.day.day, {
          day: {
            sick: this.day.sick ? true : false,
          },
        })
        .then((response) => {
          //console.log("response", response.config.data, response.data);
          if (response.status == 200) {
            this.$emit("daychanged");
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    removeDay() {
      axios
        .delete("api/day/" + this.day.day)
        .then((response) => {
          if (response.status == 200) {
            this.$emit("daychanged");
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
.sick {
  text-decoration: line-through;
  color: #999999;
}
.dayText {
  width: 100%;
  margin-left: 20px;
}
.day {
  display: flex;
  justify-content: center;
  align-items: center;
}
.trashcan {
  background: #e6e6e6;
  border: none;
  color: #ff0000;
  outline: none;
}
</style>