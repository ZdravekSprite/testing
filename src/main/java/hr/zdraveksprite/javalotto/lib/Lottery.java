package hr.zdraveksprite.javalotto.lib;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class Lottery {
    private String name;
    private Map<Date, Draw[]> draws;
    
    public Lottery (String name) {
        this.name = name;
        this.draws = new HashMap<>();
    }
    
    public void add (Date date, Draw lotto, Draw bonus) {
        this.draws.put(date, new Draw[] {lotto, bonus});
    }
    
    public Map<Date, Draw> getLottos () {
        Map<Date, Draw> lottos = new HashMap<>();
        draws.forEach((k, v) -> lottos.put(k, v[0]));
        return lottos;
    }

    public Map<Date, Draw> getBonuss () {
        Map<Date, Draw> bonuss = new HashMap<>();
        draws.forEach((k, v) -> bonuss.put(k, v[0]));
        return bonuss;
    }
    
    public int size() {
        return draws.size();
    }
    
    public List<Date> getDates() {
        List<Date> dates = new ArrayList<>();
        draws.forEach((k, v) -> dates.add(k));
        Collections.sort(dates);
        return dates;
    }

    public Object[] getArr(Date date) {
        Draw lotto = draws.get(date)[0];
        Draw bonus = draws.get(date)[1];
        Object[] test = new Object[1 + lotto.size() + bonus.size()];
        DateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd");  
        test[0] = dateFormat.format(date);
        for (int i = 0; i < lotto.size(); i++) {
            test[1 + i] = lotto.getNumbers()[i];
        }
        for (int i = 0; i < bonus.size(); i++) {
            test[1 + lotto.size() + i] = bonus.getNumbers()[i];
        }
        return test;
    }

    @Override
    public String toString() {
        return "U bazi " + name + " lutrije je " + draws.size() + " izvlačenja.";
    }
}
