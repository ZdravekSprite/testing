package hr.zdraveksprite.javalotto;

import java.nio.file.Paths;
import java.util.Arrays;
import java.util.Scanner;
 
public class RecordsFromAFile {
    
    public static void main(String[] args) {
        //Scanner scanner = new Scanner(System.in);
        //System.out.println("Name of the file:");
        //String file = scanner.nextLine();

        String file = "data.txt";
        try (Scanner fileReader = new Scanner(Paths.get(file))) {
            
            String frstLine = fileReader.nextLine();
            String[] lotteryData = frstLine.split(",");
            Lottery lottery = new Lottery(lotteryData[0]);

            int[] avrgStat = new int[35];
            
            while (fileReader.hasNextLine()) {
                String line = fileReader.nextLine();
                String[] parts = line.split(";");
                
                String day = parts[0];
                int no1 = Integer.valueOf(parts[1]);
                int no2 = Integer.valueOf(parts[2]);
                int no3 = Integer.valueOf(parts[3]);
                int no4 = Integer.valueOf(parts[4]);
                int no5 = Integer.valueOf(parts[5]);
                int noa = Integer.valueOf(parts[6]);
                int nob = Integer.valueOf(parts[7]);
                Draw tempLotto = new Draw(Integer.valueOf(lotteryData[1]), Integer.valueOf(lotteryData[2]), new int[] {no1, no2, no3, no4, no5});
                Draw tempBonus = new Draw(Integer.valueOf(lotteryData[3]), Integer.valueOf(lotteryData[4]), new int[] {noa, nob});

                lottery.add(day, tempLotto, tempBonus);
            }

            System.out.println(lottery);
            
            // test
//            Draw draw = new Draw("x", 11, 20, 27, 32, 46, 3, 5);

        } catch (Exception e) {
            System.out.println("Reading the file failed.");
        }
    }
}
