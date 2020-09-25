package hr.zdraveksprite.javalotto.ui;

import hr.zdraveksprite.javalotto.lib.Draw;
import hr.zdraveksprite.javalotto.lib.DrawType;
import hr.zdraveksprite.javalotto.lib.Lottery;
import javax.swing.JFrame;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import java.awt.Dimension;
import java.awt.GridLayout;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.nio.file.Paths;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Scanner;

public class SimpleTableDemo extends JPanel {
    private boolean DEBUG = false;
    private Lottery lottery;
    
    public SimpleTableDemo() {
        super(new GridLayout(1,0));

        String file = "data.txt";
        try (Scanner fileReader = new Scanner(Paths.get(file))) {
            
            String frstLine = fileReader.nextLine();
            String[] lotteryData = frstLine.split(";");
            lottery = new Lottery(lotteryData[0]);
            String[] lottoType = lotteryData[1].split(",");
            DrawType lottoDrawType = new DrawType(Integer.valueOf(lottoType[0]),Integer.valueOf(lottoType[1]));
            String[] bonusType = lotteryData[2].split(",");
            DrawType bonusDrawType = new DrawType(Integer.valueOf(bonusType[0]),Integer.valueOf(bonusType[1]));

            while (fileReader.hasNextLine()) {
                String line = fileReader.nextLine();
                String[] parts = line.split(";");
                
                Date day = new SimpleDateFormat("dd.MM.yyyy").parse(parts[0]);

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

        String[] columnNames = {"Date",
                                "No1",
                                "No2",
                                "No3",
                                "No4",
                                "No5",
                                "NoA",
                                "NoB"};
 
        Object[][] tableData = new Object[lottery.size()][columnNames.length];
        
        for (int i = 0; i < lottery.size(); i++) {
            
        }
        int index = 0;
        DateFormat dateFormat = new SimpleDateFormat("yyyy-mm-dd");
        for (Date date : lottery.getDates())
        {
            tableData[index] = lottery.getArr(date);
            index++;
        }
        
        final JTable table = new JTable(tableData, columnNames);
        table.setPreferredScrollableViewportSize(new Dimension(500, 70));
        table.setFillsViewportHeight(true);
 
        if (DEBUG) {
            table.addMouseListener(new MouseAdapter() {
                public void mouseClicked(MouseEvent e) {
                    printDebugData(table);
                }
            });
        }
 
        //Create the scroll pane and add the table to it.
        JScrollPane scrollPane = new JScrollPane(table);
 
        //Add the scroll pane to this panel.
        add(scrollPane);
    }
 
    private void printDebugData(JTable table) {
        int numRows = table.getRowCount();
        int numCols = table.getColumnCount();
        javax.swing.table.TableModel model = table.getModel();
 
        System.out.println("Value of data: ");
        for (int i=0; i < numRows; i++) {
            System.out.print("    row " + i + ":");
            for (int j=0; j < numCols; j++) {
                System.out.print("  " + model.getValueAt(i, j));
            }
            System.out.println();
        }
        System.out.println("--------------------------");
    }
 
    /**
     * Create the GUI and show it.  For thread safety,
     * this method should be invoked from the
     * event-dispatching thread.
     */
    private static void createAndShowGUI() {
        //Create and set up the window.
        JFrame frame = new JFrame("SimpleTableDemo");
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
 
        //Create and set up the content pane.
        SimpleTableDemo newContentPane = new SimpleTableDemo();
        newContentPane.setOpaque(true); //content panes must be opaque
        frame.setContentPane(newContentPane);
 
        //Display the window.
        frame.pack();
        frame.setVisible(true);
    }
 
    public static void main(String[] args) {
        //Schedule a job for the event-dispatching thread:
        //creating and showing this application's GUI.
        javax.swing.SwingUtilities.invokeLater(new Runnable() {
            public void run() {
                createAndShowGUI();
            }
        });
    }
}
