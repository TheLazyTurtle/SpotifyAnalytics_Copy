import javax.swing.*;
import java.awt.*;
import java.awt.event.MouseEvent;
import java.awt.event.MouseMotionListener;
import java.sql.ResultSet;
import java.util.ArrayList;

public class Graph extends Canvas implements MouseMotionListener {
    private Canvas canvas;
    private String title;
    private final int windowHeight;
    private final int windowWidth;
    private DataFetcher df;
    private ArrayList<DataPoints> data;
    private ArrayList<Bar> bars;
    private int mouseX;
    private int mouseY;

    public Graph(String title, int w, int h) {
        this.windowWidth = w;
        this.windowHeight = h;
        this.title = title;
        this.getData();
        this.makeBars();
        this.makeGraph();
        this.addMouseMotionListener(this);
    }

    public void makeGraph() {
        this.canvas = this;
        this.canvas.setSize(this.windowWidth, this.windowHeight);
        this.canvas.setVisible(true);
    }

    public void paint(Graphics g) {
        this.canvas.setBackground(Color.darkGray);
        for (Bar bar: bars) {
            // Draw the bars
            g.setColor(bar.getColor());
            g.fillRect(bar.getX(), bar.getY(), bar.getWidth(), bar.getHeight());

            g.setFont(new Font("TimesRoman", Font.BOLD, 15));
            this.drawAmount(g, bar);
            this.drawValue(g, bar);
        }
    }

    public void drawAmount(Graphics g, Bar bar) {
        FontMetrics fm = g.getFontMetrics();
        int w = fm.stringWidth(String.valueOf(bar.getAmount()));
        int h = fm.getAscent();

        g.setColor(Color.black);
        g.drawString(String.valueOf(bar.getAmount()), bar.getX() + (bar.getWidth() / 2) - (w/2), bar.getY() + (bar.getHeight()/2) + (h/2));
    }

    public void drawValue(Graphics g, Bar bar) {
        FontMetrics fm = g.getFontMetrics();
        String value = bar.getValue();
        int w = fm.stringWidth(value);
        int h = fm.getAscent();

        while (w > bar.getWidth()) {
            w = fm.stringWidth(value);
            value = value.substring(0, value.length()-1);
        }

        g.setColor(Color.black);
        g.drawString(value, bar.getX() + (bar.getWidth()/2) - (w/2), bar.getY());
    }

    public void makeBars() {
        int index = 0;
        int rowCount = this.getRowCount();
        int max = ((this.getMaxAmount() + 99) / 100) * 100;
        this.bars = new ArrayList<Bar>();

        if (data != null) {
            for (DataPoints dataPoint: data) {
                this.bars.add(new Bar(windowWidth, windowHeight, rowCount, max, dataPoint.getTimes(), index, dataPoint.getName()));
                index++;
            }
        }
    }

    private int getMaxAmount() {
        int max = 0;

        for (DataPoints dataPoint: data) {
            if (dataPoint.getTimes() > max) {
                max = dataPoint.getTimes();
            }
        }
        return max;
    }

    private int getRowCount() {
        return this.data.size();
    }

    // This will get all the data for the bars
    private void getData() {
        this.df = new DataFetcher();
        try {
            resultSetToArrayList(df.getData());
        } catch (Exception e) {
            System.out.println(e);
        }
    }

    private void resultSetToArrayList(ResultSet rs) {
        try {
            data = new ArrayList<DataPoints>();

            while (rs.next()) {
                data.add(new DataPoints(rs.getString("songName"), rs.getInt("times")));
            }
        } catch (Exception e) {
            System.out.println(e);
        }
    }

    private void showOnHover() {
        for (Bar bar: this.bars) {
            int barX = bar.getX();
            int barY = bar.getY();
            int barW = bar.getWidth();
            int barH = bar.getHeight();

            if (this.mouseX >= barX && this.mouseX <= barX + barW && this.mouseY >= barY && this.mouseY <= barY+barH) {
                getGraphics().drawString(bar.getValue(), barX + (barW/2), barY + (barH/2));
            }
        }
    }

    // This one is just necessary because of the mouse event thing
    @Override
    public void mouseDragged(MouseEvent e) {
    }

    @Override
    public void mouseMoved(MouseEvent e) {
        this.mouseX = e.getX();
        this.mouseY = e.getY();
        this.showOnHover();
    }
}
