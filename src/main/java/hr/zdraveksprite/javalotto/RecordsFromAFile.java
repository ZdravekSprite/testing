package hr.zdraveksprite.javalotto;

import java.nio.file.Paths;
import java.util.Scanner;
 
public class RecordsFromAFile {
    
    public static void main(String[] args) {
        //Scanner scanner = new Scanner(System.in);
        //System.out.println("Name of the file:");
        //String file = scanner.nextLine();

        String file = "data.txt";
        try (Scanner fileReader = new Scanner(Paths.get(file))) {
            
            String frstLine = fileReader.nextLine();
            String[] lotteryData = frstLine.split(";");
            Lottery lottery = new Lottery(lotteryData[0]);
            String[] lottoType = lotteryData[1].split(",");
            DrawType lottoDrawType = new DrawType(Integer.valueOf(lottoType[0]),Integer.valueOf(lottoType[1]));
            String[] bonusType = lotteryData[2].split(",");
            DrawType bonusDrawType = new DrawType(Integer.valueOf(bonusType[0]),Integer.valueOf(bonusType[1]));

            while (fileReader.hasNextLine()) {
                String line = fileReader.nextLine();
                String[] parts = line.split(";");
                
                String day = parts[0];

                String[] lottoStringArr = parts[1].split(",");
                int[] lottoIntArr = new int[lottoDrawType.getX()];
                for (int i = 0; i < lottoDrawType.getX(); i++) {
                    lottoIntArr[i] = Integer.valueOf(lottoStringArr[i]);
                }

                String[] bonusStringArr = parts[2].split(",");
                int[] bonusIntArr = new int[bonusDrawType.getX()];
                for (int i = 0; i < bonusDrawType.getX(); i++) {
                    bonusIntArr[i] = Integer.valueOf(bonusStringArr[i]);
                }

                Draw tempLotto = new Draw(lottoDrawType, lottoIntArr);
                Draw tempBonus = new Draw(bonusDrawType, bonusIntArr);

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
