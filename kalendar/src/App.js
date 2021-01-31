import { useState, useEffect } from 'react'
import IPTable from './components/IPTable'
import Button from './components/Button'

function App() {
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
  const optionsOdbitak = [
    { value: 4000, label: 'Osnovni osobni odbitak' },
    { value: 5750, label: 'Jedno dijete' },
    { value: 8250, label: 'Dva dijeteta' },
    { value: 11750, label: 'Tri dijeteta' },
  ]
  const [year, setYear] = useState(new Date().getFullYear())
  const [month, setMonth] = useState(new Date().getMonth() + 1)
  const [bruto, setBruto] = useState(530000)
  const [prijevoz, setPrijevoz] = useState(400)
  const [prirez, setPrirez] = useState(12)
  const [odbitak, setOdbitak] = useState(4000)
  const [days, setDays] = useState([])
  useEffect(() => {
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
      const holy = holidays.some(day => day.date === d + '.' + m + '.' + y) ? true : false
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

  const handleBrutoChange = (event) => {
    if (isNaN(event.target.value)) {
      console.log(event.target.value)
    } else {
      setBruto(event.target.value * 100)
    }
  }

  function findDay(day, m, y) {
    return days.find(d => d.day === day + '.' + m + '.' + y)
  }
  function getAllDaysInMonth(m, y) {
    var daysInMonth = [];
    for (var i = 1; i <= new Date(y, m, 0).getDate(); i++) {
      if (findDay(i, m, y)) {
        daysInMonth.push(findDay(i, m, y))
      }
    }
    //console.log('daysInMonth', daysInMonth)
    return daysInMonth;
  }
  function onPlus(day) {
    const newHours = day.hours < 24 ? day.hours + 1 : 24
    //console.log('click', day)
    setDays(days.map(d => d.day === day.day ? { ...d, hours: newHours, sick: false } : d))
  }
  function onPlus8(day) {
    const newHours = day.hours < 16 ? day.hours + 8 : 24
    //console.log('click', day)
    setDays(days.map(d => d.day === day.day ? { ...d, hours: newHours, sick: false } : d))
  }
  function onMinus(day) {
    const newHours = day.hours > 0 ? day.hours - 1 : 0
    //console.log('click', day)
    setDays(days.map(d => d.day === day.day ? { ...d, hours: newHours, sick: false } : d))
  }
  function onNotWork(day) {
    //console.log('click', day)
    setDays(days.map(d => d.day === day.day ? { ...d, hours: 0 } : d))
  }
  function onSick(day) {
    //console.log('click', day)
    setDays(days.map(d => d.day === day.day ? { ...d, sick: !d.sick, hours: 0 } : d))
  }
  const allDaysInMonth = getAllDaysInMonth(month, year)
  if (allDaysInMonth.length === 0) return ('')
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
        <span>
          Bruto <input value={bruto / 100} onChange={handleBrutoChange} /> kn
        </span>
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
        <select
          value={odbitak}
          onChange={(e) => setOdbitak(e.target.value)}
        >
          {optionsOdbitak.map(o => (
            <option key={o.value} value={o.value}>{o.label}</option>
          ))}
        </select>
      </div>
      <div className="row">
        <div className="col-9 col-s-12">
          <IPTable
            allDaysInMonth={allDaysInMonth}
            bruto={bruto}
            prijevoz={prijevoz}
            year={year}
            month={month}
            odbitak={odbitak}
            prirez={prirez}
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
                  <div className='row'>
                    <div className="float">
                      {d.day} {(d.sick ? 'B' + d.def : d.hours) + '' + (d.holy ? '(' + d.def + ')' : '')}
                    </div>
                    <div className="float">
                      <Button
                        color={'aqua'}
                        text={'+8'}
                        onClick={() => onPlus8(d)}
                      />
                      <Button
                        color={'green'}
                        text={'+'}
                        onClick={() => onPlus(d)}
                      />
                      <Button
                        color={'red'}
                        text={'-'}
                        onClick={() => onMinus(d)}
                      />
                      <Button
                        color={'beige'}
                        text={'x'}
                        onClick={() => onNotWork(d)}
                      />
                      <Button
                        color={'aquamarine'}
                        text={'B'}
                        onClick={() => onSick(d)}
                      />
                    </div>
                  </div>
                </li>
              ))}
            </ul>
          </div>
        </div>
      </div>
      <div className="footer">
        <p>Sprite &copy; 2021.</p>
      </div>
    </div>
  );
}

export default App;
