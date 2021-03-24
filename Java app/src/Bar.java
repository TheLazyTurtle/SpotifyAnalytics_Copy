import javax.swing.*;
import java.awt.*;

public class Bar {
    private final int windowWidth;
    private final int windowHeight;
    private final int padding = 5;
    private final Color[] colors = {Color.blue, Color.cyan, Color.green, Color.magenta, Color.orange, Color.pink, Color.red};
    private final int width;
    private final int height;
    private final int x;
    private final int y;
    private final Color color;
    private final int amount;
    private final String value;

    public Bar(int windowWidth, int windowHeight, int totalRows, int maxAmount, int amount, int place, String value) {
        this.windowWidth = windowWidth;
        this.windowHeight = windowHeight;
        this.width = this.calculateBarWidth(totalRows);
        this.height = this.calculateBarHeight(maxAmount, amount);
        this.x = place * (width + this.padding);
        this.y = (this.windowHeight - height);
        this.amount = amount;
        this.value = value;
        this.color = this.chooseBarColor(place);
    }

    // This will calculate how long the bar will be
    private int calculateBarHeight(int maxAmount, int amount) {
        double height = (double)maxAmount / amount;
        height = (double)this.windowHeight / height;
        return (int)height;
    }

    // This will calculate how wide a bar can be
    private int calculateBarWidth(int amountRows) {
        return (this.windowWidth / amountRows) - this.padding;
    }

    // This will choose a color for the bar
    private Color chooseBarColor(int index) {
        while (index >= this.colors.length) {
            index -= this.colors.length;
        }
        return colors[index];
    }

    public Color[] getColors() {
        return colors;
    }

    public int getWidth() {
        return width;
    }

    public int getHeight() {
        return height;
    }

    public int getX() {
        return x;
    }

    public int getY() {
        return y;
    }

    public int getAmount() {
        return amount;
    }

    public String getValue() {
        return value;
    }

    public Color getColor() {
        return color;
    }
}
