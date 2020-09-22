package hr.zdraveksprite.javalotto;

public class Draw {
    private String date;
    private int no1;
    private int no2;
    private int no3;
    private int no4;
    private int no5;
    private int noa;
    private int nob;
    
    public Draw(String date, int no1, int no2, int no3, int no4, int no5, int noa, int nob) {
        this.date = date;
        this.no1 = no1;
        this.no2 = no2;
        this.no3 = no3;
        this.no4 = no4;
        this.no5 = no5;
        this.noa = noa;
        this.nob = nob;
    }
    
    public boolean containsA(int value) {
        if (value == no1) return true;
        if (value == no2) return true;
        if (value == no3) return true;
        if (value == no4) return true;
        if (value == no5) return true;
        return false;
    }
    
    public boolean containsB(int value) {
        if (value == noa) return true;
        if (value == nob) return true;
        return false;
    }
    
    @Override
    public String toString() {
        return date + " dana su izvučeni brojevi " + no1 + ", " + no2 + ", " + no3 + ", " + no4 + ", " + no5 + " iz velikog bubnja i " + noa + " i " + nob + " iz malog bubnja";
    }
}
