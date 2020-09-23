package hr.zdraveksprite.javalotto;

import java.util.HashMap;
import java.util.Map;

public class Lottery {
    private String name;
    private Map<String, Draw[]> draws;
    
    public Lottery (String name) {
        this.name = name;
        this.draws = new HashMap<>();
    }
    
    public void add (String date, Draw lotto, Draw bonus) {
        this.draws.put(date, new Draw[] {lotto, bonus});
    }

    @Override
    public String toString() {
        return "U bazi " + name + " lutrije je " + draws.size() + " izvlačenja.";
    }
}
