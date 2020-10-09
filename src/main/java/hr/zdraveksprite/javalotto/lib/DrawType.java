package hr.zdraveksprite.javalotto.lib;

public class DrawType {
    private int x;
    private int y;
    
    public DrawType(int drawX, int drawY) {
        this.x = drawX;
        this.y = drawY;
    }
    
    public int getX() {
        return x;
    }
    
    public int getY() {
        return y;
    }
    
    @Override
    public String toString() {
        return x + "/" + y;
    }
    
}
