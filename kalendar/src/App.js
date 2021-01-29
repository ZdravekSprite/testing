import { useState, useEffect } from 'react'
import TableRow from './components/TableRow'

function App() {
  const holidays = [
    { date: '1.1.2020', text: 'Nova godina' },
    { date: '6.1.2020', text: 'Sveta tri kralja (Bogojavljenje)' },
    { date: '12.4.2020', text: 'Uskrs' },
    { date: '13.4.2020', text: 'Uskrsni ponedjeljak' },
    { date: '1.5.2020', text: 'Praznik rada' },
    { date: '30.5.2020', text: 'Dan državnosti' },
    { date: '11.6.2020', text: 'Tijelovo' },
    { date: '22.6.2020', text: 'Dan antifašističke borbe' },
    { date: '5.8.2020', text: 'Dan pobjede i domovinske zahvalnosti i Dan hrvatskih branitelja' },
    { date: '15.8.2020', text: 'Velika Gospa' },
    { date: '1.11.2020', text: 'Dan svih svetih' },
    { date: '18.11.2020', text: 'Dan sjećanja na žrtve Domovinskog rata i Dan sjećanja na žrtvu Vukovara i Škabrnje' },
    { date: '25.12.2020', text: 'Božić' },
    { date: '26.12.2020', text: 'Sveti Stjepan' },
    { date: '1.1.2021', text: 'Nova godina' },
    { date: '6.1.2021', text: 'Sveta tri kralja (Bogojavljenje)' },
    { date: '4.4.2021', text: 'Uskrs' },
    { date: '5.4.2021', text: 'Uskrsni ponedjeljak' },
    { date: '1.5.2021', text: 'Praznik rada' },
    { date: '30.5.2021', text: 'Dan državnosti' },
    { date: '3.6.2021', text: 'Tijelovo' },
    { date: '22.6.2021', text: 'Dan antifašističke borbe' },
    { date: '5.8.2021', text: 'Dan pobjede i domovinske zahvalnosti i Dan hrvatskih branitelja' },
    { date: '15.8.2021', text: 'Velika Gospa' },
    { date: '1.11.2021', text: 'Dan svih svetih' },
    { date: '18.11.2021', text: 'Dan sjećanja na žrtve Domovinskog rata i Dan sjećanja na žrtvu Vukovara i Škabrnje' },
    { date: '25.12.2021', text: 'Božić' },
    { date: '26.12.2021', text: 'Sveti Stjepan' },
  ]
  const optionsYears = [
    { value: 2020, label: 2020 },
    { value: 2021, label: 2021 },
  ]
  const optionsMonths = [
    { value: 1, label: 'siječanj' },
    { value: 2, label: 'veljača' },
    { value: 3, label: 'ožujak' },
    { value: 4, label: 'travanj' },
    { value: 5, label: 'svibanj' },
    { value: 6, label: 'lipanj' },
    { value: 7, label: 'srpanj' },
    { value: 8, label: 'kolovoz' },
    { value: 9, label: 'rujan' },
    { value: 10, label: 'listopad' },
    { value: 11, label: 'studeni' },
    { value: 12, label: 'prosinac' },
  ]
  const optionsPrijevoz = [
    { value: 360, label: 'ZET Zg' },
    { value: 400, label: 'ZET + HŽ Zg' },
    { value: 600, label: 'ZET Zg + 2. zona' },
  ]
  const optionsPrirez = [
    { value: 12, label: 'Zaprešić' },
    { value: 15, label: 'Zagreb' },
  ]
  const [year, setYear] = useState(new Date().getFullYear())
  const [month, setMonth] = useState(new Date().getMonth() + 1)
  const [bruto, setBruto] = useState(530000)
  const [prijevoz, setPrijevoz] = useState(400)
  const [prirez, setPrirez] = useState(12)
  const [days, setDays] = useState([])
  useEffect(() => {
    const getDays = () => {
      const makeDays = makeAllDaysInMonth(month, year)
      setDays(d => d.concat(makeDays.filter(x => !d.some(y => y.day === x.day))))
    }
    function makeAllDaysInMonth(m, y) {
      var daysInMonth = [];
      for (var i = 1; i <= new Date(y, m, 0).getDate(); i++) {
        daysInMonth.push(makeDay(i, m, y));
      }
      return daysInMonth;
    }
    function makeDay(d, m, y) {
      const holy = holidays.some(d => d.date === d + '.' + m + '.' + y) ? true : false
      const dayIndex = new Date(m + '/' + d + '/' + y).getDay()
      const day = {
        day: d + '.' + m + '.' + y,
        holy: holy,
        sick: false,
        def: dayIndex === 0 ? 0 : dayIndex < 6 ? 7 : 5,
        hours: 0
      }
      return day
    }
        getDays()
  }, [month, year])

  function getAllDaysInMonth(m, y) {
    var daysInMonth = [];
    for (var i = 1; i <= new Date(y, m, 0).getDate(); i++) {
      const findDay = days.find(d => d.day === i + '.' + m + '.' + y)
      if (findDay) {
        daysInMonth.push(findDay)
      }
    }
    return daysInMonth;
  }
  const allDaysInMonth = getAllDaysInMonth(month, year)
  const hoursNorm = allDaysInMonth.reduce((sum, d) => sum + d.def, 0)
  const perHour = (bruto / hoursNorm / 100).toFixed(2)
  // 1.7a Praznici. Blagdani, izbori
  const h17a = allDaysInMonth.filter(d => d.holy).reduce((sum, d) => sum + d.def, 0)
  const kn17a = h17a * perHour
  // 1.7d Bolovanje do 42 dana
  const h17d = allDaysInMonth.filter(d => d.sick).reduce((sum, d) => sum + d.def, 0)
  const kn17d = h17d * perHour * 0.7588
  // 1.1 Za redoviti rad
  const h11 = hoursNorm - h17a - h17d
  const kn11 = h11 * perHour
  // 1.7e Dodatak za rad nedjeljom
  const h17e = allDaysInMonth.filter(d => d.def === 0).reduce((sum, d) => sum + d.hours, 0)
  const kn17e = h17e * perHour * 0.35
  // 1.7f Dodadatak za rad na praznik
  const h17f = allDaysInMonth.filter(d => d.holy).reduce((sum, d) => sum + d.hours, 0)
  const kn17f = h17f * perHour * 0.5
  // 3. PROPISANI ILI UGOVORENI DODACI NA PLAĆU RADNIKA I NOVČANI IZNOSI PO TOJ OSNOVI
  const kn3 = prijevoz
  // 5. OSNOVICA ZA OBRAČUN DOPRINOSA
  const kn5 = kn11 + kn17a + kn17d + kn17e + kn17f
  // 4. ZBROJENI IZNOSI PRIMITAKA PO SVIM OSNOVAMA PO STAVKAMA 1. DO 3.
  const kn4 = kn5 + kn3
  // 6.1. za mirovinsko osiguranje na temelju generacijske solidarnosti (I. STUP)
  const kn61 = kn5 * 0.15
  // 6.2. za mirovinsko osiguranje na temelju individualne kapitalizirane štednje (II. STUP)
  const kn62 = kn5 * 0.05
  // 7. DOHODAK
  const kn7 = kn5 - kn61 - kn62
  if (allDaysInMonth.length === 0) return('')
  return (
    <div className="App">
      <div className="header">
        <select
          value={year}
          onChange={(e) => setYear(e.target.value)}
        >
          {optionsYears.map(o => (
            <option key={o.value} value={o.value}>{o.label}</option>
          ))}
        </select>
        <select
          value={month}
          onChange={(e) => setMonth(e.target.value)}
        >
          {optionsMonths.map(o => (
            <option key={o.value} value={o.value}>{o.label}</option>
          ))}
        </select>
        <span>{(bruto / 100).toFixed(2)}kn</span>
        <select
          value={prijevoz}
          onChange={(e) => setPrijevoz(e.target.value)}
        >
          {optionsPrijevoz.map(o => (
            <option key={o.value} value={o.value}>{o.label}</option>
          ))}
        </select>
        <select
          value={prirez}
          onChange={(e) => setPrirez(e.target.value)}
        >
          {optionsPrirez.map(o => (
            <option key={o.value} value={o.value}>{o.label}</option>
          ))}
        </select>
      </div>
      <div className="row">
        <div className="col-9 col-s-9">
          <div className="row">
            <div className="col-6 col-s-6">
              <b>OBRAČUN ISPLAĆENE PLAĆE</b>
            </div>
            <div className="col-6 col-s-6 right">
              <b>Obrazac IP1</b>
            </div>
          </div>
          <div className="row">
            <div className="col-6 col-s-6">
              <ul>
                <li><b>I. PODACI O POSLODAVCU</b></li>
                <li>1. Tvrtka/ Ime i prezime: ____</li>
                <li>2. Sjedište / Adresa: ____</li>
                <li>3. Osobni identifikacijski broj: ____</li>
                <li>4. IBAN broj računa ____ kod ____</li>
              </ul>
            </div>
            <div className="col-6 col-s-6">
              <ul>
                <li><b>II. PODACI O RADNIKU/RADNICI</b></li>
                <li>1. Ime i prezime: <b>____</b></li>
                <li>2. Adresa: ____</li>
                <li>3. Osobni identifikacijski broj: ____</li>
                <li>4. IBAN broj računa ____ kod ____</li>
                <li>5. IBAN broj računa iz čl. 212. Ovršnog zakona ____ kod ____</li>
              </ul>
            </div>
          </div>
          <div className="row">
            <b>III. RAZDOBLJE NA KOJE SE PLAĆA ODNOSI:</b> GODINA {year}, MJESEC {month} DANI U MJESECU OD 1 DO {allDaysInMonth.length}
          </div>
          <TableRow
            opis='1. OPIS PLAĆE'
            sati='SATI'
            iznos='IZNOS'
            bold='true'
          />
          <TableRow
            opis='1.1. Za redoviti rad:'
            sati={h11}
            iznos={kn11}
          />
          <TableRow
            opis='1.7a Praznici. Blagdani, izbori:'
            sati={h17a}
            iznos={kn17a}
          />
          <TableRow
            opis='1.7d Bolovanje do 42 dana:'
            sati={h17d}
            iznos={kn17d}
          />
          <TableRow
            opis='1.7e Dodatak za rad nedjeljom'
            sati={h17e}
            iznos={kn17e}
          />
          <TableRow
            opis='1.7f Dodadatak za rad na praznik'
            sati={h17f}
            iznos={kn17f}
          />
          <TableRow
            opis='2. OSTALI OBLICI RADA TEMELJEM KOJIH OSTVARUJE PRAVO NA UVEĆANJE PLAĆE PREMA
            KOLEKTIVNOM UGOVORU, PRAVILNIKU O RADU ILI UGOVORU O RADU I NOVČANI IZNOS
            PO TOJ OSNOVI (SATI PRIPRAVNOSTI)'
          />
          <TableRow
            opis='3. PROPISANI ILI UGOVORENI DODACI NA PLAĆU RADNIKA I NOVČANI IZNOSI PO TOJ OSNOVI'
            iznos={kn3}
          />
          <TableRow
            opis='3.1. Prijevoz'
            iznos={prijevoz}
          />
          <TableRow
            opis='4. ZBROJENI IZNOSI PRIMITAKA PO SVIM OSNOVAMA PO STAVKAMA 1. DO 3.'
            iznos={kn4}
          />
          <TableRow
            opis='5. OSNOVICA ZA OBRAČUN DOPRINOSA'
            iznos={kn5}
            bold='true'
          />
          <TableRow
            opis='6. VRSTE I IZNOSI DOPRINOSA ZA OBVEZNA OSIGURANJA KOJA SE OBUSTAVLJAJU IZ PLAĆE'
          />
          <TableRow
            opis='6.1. za mirovinsko osiguranje na temelju generacijske solidarnosti (I. STUP)'
            iznos={kn61}
          />
          <TableRow
            opis='6.2 za mirovinsko osiguranje na temelju individualne kapitalizirane štednje (II. STUP)'
            iznos={kn62}
          />
          <TableRow
            opis='7. DOHODAK'
            iznos={kn7}
            bold='true'
          />
          <TableRow
            opis='8. OSOBNI ODBITAK 1.00 / 4000.00'
          />
          <TableRow
            opis='9. POREZNA OSNOVICA'
          />
          <TableRow
            opis='10. IZNOS PREDUJMA POREZA I PRIREZA POREZU NA DOHODAK'
          />
          <TableRow
            opis='20.00 %'
          />
          <TableRow
            opis={'Prirez ' + prirez + '.00 %'}
          />
          <TableRow
            opis='11. NETO PLAĆA'
            bold='true'
          />
          <TableRow
            opis='12. NAKNADE UKUPNO'
          />
          <TableRow
            opis='13. NETO + NAKNADE'
            bold='true'
          />
          <TableRow
            opis='14. OBUSTAVE UKUPNO'
          />
          <TableRow
            opis='15. IZNOS PLAĆE/NAKNADE PLAĆE ISPLAĆEN RADNIKU NA REDOVAN RAČUN'
          />
          <TableRow
            opis='16. IZNOS PLAĆE/NAKNADE PLAĆE ISPLAĆEN RADNIKU NA RAČUN IZ ČL.212. OVRŠNOG ZAKONA'
          />
        </div>
        <div className="col-3 col-s-12">
          <div className="aside">
            <ul>
              {allDaysInMonth.map(d => (
                <li
                  key={d.day}
                  style={{
                    color: d.holy ? 'red' : 'black',
                    backgroundColor: d.def > 5 ? 'white' : d.def < 5 ? 'lightblue' : 'cyan'
                  }}
                >
                  {d.day} - {d.hours} - {d.def}
                </li>
              ))}
            </ul>
          </div>
        </div>
      </div>
      <div className="footer">
        <p>Sprite 2021.</p>
      </div>
    </div>
  );
}

export default App;
