import Slider from "../slider/Slider";
import Wrapper from "../graph/GraphWrapper";
import { graphs, Graph } from "../graph/Graphs";

function HomePage() {
    return (
        <>
            <Slider />
            <section className="w-100">
                {new graphs().graphs.map((graph: Graph) => (
                    <div key={graph.name} className="graph pt-3" id={graph.name}>
                        <Wrapper key={graph.name} graph={graph} />
                    </div>
                ))}
            </section>
        </>
    );
}

export default HomePage
