public class DataPoints {
    private String name;
    private int times;

    public DataPoints(String name, int times) {
        this.name = name;
        this.times = times;
    }

    public String getName() {
        return name;
    }

    public int getTimes() {
        return times;
    }

    public String toString() {
        return this.name + " " + this.times;
    }
}
