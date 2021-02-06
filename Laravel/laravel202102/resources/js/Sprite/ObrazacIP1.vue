<template>
  <div>
    <div class="row">
      <div class="col-6 col-s-6">
        <b>OBRAČUN ISPLAĆENE PLAĆE</b>
      </div>
      <div class="col-6 col-s-6 right">
        <b>Obrazac IP1</b>
      </div>
    </div>
    <div class="row">
      <div class="col-6 col-s-6">
        <ul>
          <li><b>I. PODACI O POSLODAVCU</b></li>
          <li>1. Tvrtka/ Ime i prezime: ____</li>
          <li>2. Sjedište / Adresa: ____</li>
          <li>3. Osobni identifikacijski broj: ____</li>
          <li>4. IBAN broj računa ____ kod ____</li>
        </ul>
      </div>
      <div class="col-6 col-s-6">
        <ul>
          <li><b>II. PODACI O RADNIKU/RADNICI</b></li>
          <li>
            1. Ime i prezime: <b>{{ $page.props.user.name }}</b>
          </li>
          <li>2. Adresa: ____</li>
          <li>3. Osobni identifikacijski broj: ____</li>
          <li>4. IBAN broj računa ____ kod ____</li>
          <li>5. IBAN broj računa iz čl. 212. Ovršnog zakona ____ kod ____</li>
        </ul>
      </div>
    </div>
    <div class="row">
      <b>III. RAZDOBLJE NA KOJE SE PLAĆA ODNOSI:</b> GODINA {{ year }}, MJESEC
      {{ month }} DANI U MJESECU OD 1 DO {{ allDaysInMonth.length }}
    </div>
    <table-row
      :opis="'1. OPIS PLAĆE'"
      :sati="'SATI'"
      :iznos="'IZNOS'"
      :bold="true"
    />
    <table-row :opis="'1.1. Za redoviti rad:'" :sati="h1_1" :iznos="kn1_1" />
    <table-row
      :opis="'1.7a Praznici. Blagdani, izbori:'"
      :notShow="h17a === 0"
      :sati="h17a"
      :iznos="kn17a"
    />
    <table-row
      :opis="'1.7d Bolovanje do 42 dana:'"
      :notShow="h17d === 0"
      :sati="h17d"
      :iznos="kn17d"
    />
    <table-row
      :opis="'1.7e Dodatak za rad nedjeljom'"
      :notShow="h17e === 0"
      :sati="h17e"
      :iznos="kn17e"
    />
    <table-row
      :opis="'1.7f Dodadatak za rad na praznik'"
      :notShow="h17f === 0"
      :sati="h17f"
      :iznos="kn17f"
    />
    <table-row
      :opis="'2. OSTALI OBLICI RADA TEMELJEM KOJIH OSTVARUJE PRAVO NA UVEĆANJE PLAĆE PREMA KOLEKTIVNOM UGOVORU, PRAVILNIKU O RADU ILI UGOVORU O RADU I NOVČANI IZNOS PO TOJ OSNOVI (SATI PRIPRAVNOSTI)'"
    />
  </div>
</template>

<script>
import TableRow from "@/Sprite/TableRow";
export default {
  components: {
    TableRow,
  },
  mounted() {
    console.log("obrazac mounted");
  },
  props: {
    year: {
      default: new Date().getFullYear(),
    },
    month: {
      default: new Date().getMonth() + 1,
    },
    bruto: {
      default: 530000,
    },
  },
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
  methods: {
    makeDay: function (d, m, y) {
      var sick = false;
      var hours = 0;
      const holy = this.holidays.some(
        (day) => day.date === d + "." + m + "." + y
      )
        ? true
        : false;
      const work = JSON.parse(this.$page.props.user.work_days);
      //console.log(work);
      if (work.some((day) => day.d === d + "." + m + "." + y)) {
        const work_day = work.find((day) => day.d === d + "." + m + "." + y);
        if (work_day.s) {
          sick = work_day.s;
        } else {
          hours = work_day.h.reduce((sum, h) => sum + h.d.split(":")[0] * 1, 0);
        }
      }
      const dayIndex = new Date(m + "/" + d + "/" + y).getDay();
      const day = {
        day: d + "." + m + "." + y,
        holy: holy,
        sick: sick,
        def: dayIndex === 0 ? 0 : dayIndex < 6 ? 7 : 5,
        hours: hours,
      };
      return day;
    },
    makeAllDaysInMonth: function (m, y) {
      var daysInMonth = [];
      for (var i = 1; i <= new Date(y, m, 0).getDate(); i++) {
        daysInMonth.push(this.makeDay(i, m, y));
      }
      return daysInMonth;
    },
  },
  computed: {
    allDaysInMonth() {
      //console.log(this.makeAllDaysInMonth(this.month, this.year));
      return this.makeAllDaysInMonth(this.month, this.year);
    },
    hoursNorm() {
      return this.allDaysInMonth.reduce((sum, d) => sum + d.def, 0);
    },
    hoursWork() {
      return this.allDaysInMonth.reduce((sum, d) => sum + d.hours, 0);
    },
    perHour() {
      return (this.bruto / this.hoursNorm / 100).toFixed(2);
    },
    // 1.7a Praznici. Blagdani, izbori
    h17a() {
      return this.allDaysInMonth
        .filter((d) => d.holy)
        .reduce((sum, d) => sum + d.def, 0);
    },
    kn17a() {
      return this.h17a * this.perHour;
    },
    overWork() {
      return this.hoursWork - this.hoursNorm + h17a;
    },
    // 1.7d Bolovanje do 42 dana
    h17d() {
      return this.allDaysInMonth
        .filter((d) => d.sick)
        .reduce((sum, d) => sum + d.def, 0);
    },
    kn17d() {
      return this.h17d * this.perHour * 0.7588;
    },
    // 1.1 Za redoviti rad
    h1_1() {
      return this.hoursWork > this.hoursNorm - this.h17a - this.h17d
        ? this.hoursNorm - this.h17a - this.h17d
        : this.hoursWork;
    },
    kn1_1() {
      return this.h1_1 * this.perHour;
    },
    // 1.7e Dodatak za rad nedjeljom
    h17e() {
      return this.allDaysInMonth
        .filter((d) => d.def === 0)
        .reduce((sum, d) => sum + d.hours, 0);
    },
    kn17e() {
      return this.h17e * this.perHour * 0.35;
    },
    // 1.7f Dodadatak za rad na praznik
    h17f() {
      return this.allDaysInMonth
        .filter((d) => d.holy)
        .reduce((sum, d) => sum + d.hours, 0);
    },
    kn17f() {
      return this.h17f * this.perHour * 0.5;
    },
  },
};
</script>