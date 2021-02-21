<template>
  <div>
    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
      <kalendar
        :year="year"
        :month="month"
        :holidays="holidays"
      />
    </div>
  </div>
</template>

<script>
import kalendar from "@/Sprite/Kalendar";

export default {
  components: {
    kalendar,
  },
  props: ["year", "month"],
  data: () => {
    return {
      holidays: [
        { date: "1.1.2020", text: "Nova godina" },
        { date: "6.1.2020", text: "Sveta tri kralja (Bogojavljenje)" },
        { date: "12.4.2020", text: "Uskrs" },
        { date: "13.4.2020", text: "Uskrsni ponedjeljak" },
        { date: "1.5.2020", text: "Praznik rada" },
        { date: "30.5.2020", text: "Dan državnosti" },
        { date: "11.6.2020", text: "Tijelovo" },
        { date: "22.6.2020", text: "Dan antifašističke borbe" },
        {
          date: "5.8.2020",
          text:
            "Dan pobjede i domovinske zahvalnosti i Dan hrvatskih branitelja",
        },
        { date: "15.8.2020", text: "Velika Gospa" },
        { date: "1.11.2020", text: "Dan svih svetih" },
        {
          date: "18.11.2020",
          text:
            "Dan sjećanja na žrtve Domovinskog rata i Dan sjećanja na žrtvu Vukovara i Škabrnje",
        },
        { date: "25.12.2020", text: "Božić" },
        { date: "26.12.2020", text: "Sveti Stjepan" },
        { date: "1.1.2021", text: "Nova godina" },
        { date: "6.1.2021", text: "Sveta tri kralja (Bogojavljenje)" },
        { date: "4.4.2021", text: "Uskrs" },
        { date: "5.4.2021", text: "Uskrsni ponedjeljak" },
        { date: "1.5.2021", text: "Praznik rada" },
        { date: "30.5.2021", text: "Dan državnosti" },
        { date: "3.6.2021", text: "Tijelovo" },
        { date: "22.6.2021", text: "Dan antifašističke borbe" },
        {
          date: "5.8.2021",
          text:
            "Dan pobjede i domovinske zahvalnosti i Dan hrvatskih branitelja",
        },
        { date: "15.8.2021", text: "Velika Gospa" },
        { date: "1.11.2021", text: "Dan svih svetih" },
        {
          date: "18.11.2021",
          text:
            "Dan sjećanja na žrtve Domovinskog rata i Dan sjećanja na žrtvu Vukovara i Škabrnje",
        },
        { date: "25.12.2021", text: "Božić" },
        { date: "26.12.2021", text: "Sveti Stjepan" },
      ],
    };
  },
};
</script>

<style>
.row::after {
  content: "";
  clear: both;
  display: table;
}

[class*="col-"] {
  float: left;
  padding: 5px;
}

.right {
  text-align: right;
}

.bold {
  font-weight: bold;
}

.aside {
  background-color: #33b5e5;
  padding: 15px;
  color: #ffffff;
  text-align: center;
  font-size: 14px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
}

.float {
  float: left;
  min-width: 100px;
}

/* For mobile phones: */
[class*="col-"] {
  width: 100%;
}

@media only screen and (min-width: 600px) {
  /* For tablets: */
  .col-s-1 {
    width: 8.33%;
  }
  .col-s-2 {
    width: 16.66%;
  }
  .col-s-3 {
    width: 25%;
  }
  .col-s-4 {
    width: 33.33%;
  }
  .col-s-5 {
    width: 41.66%;
  }
  .col-s-6 {
    width: 50%;
  }
  .col-s-7 {
    width: 58.33%;
  }
  .col-s-8 {
    width: 66.66%;
  }
  .col-s-9 {
    width: 75%;
  }
  .col-s-10 {
    width: 83.33%;
  }
  .col-s-11 {
    width: 91.66%;
  }
  .col-s-12 {
    width: 100%;
  }
}
@media only screen and (min-width: 768px) {
  /* For desktop: */
  .col-1 {
    width: 8.33%;
  }
  .col-2 {
    width: 16.66%;
  }
  .col-3 {
    width: 25%;
  }
  .col-4 {
    width: 33.33%;
  }
  .col-5 {
    width: 41.66%;
  }
  .col-6 {
    width: 50%;
  }
  .col-7 {
    width: 58.33%;
  }
  .col-8 {
    width: 66.66%;
  }
  .col-9 {
    width: 75%;
  }
  .col-10 {
    width: 83.33%;
  }
  .col-11 {
    width: 91.66%;
  }
  .col-12 {
    width: 100%;
  }
}
</style>