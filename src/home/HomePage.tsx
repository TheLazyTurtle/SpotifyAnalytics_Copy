import Wrapper from "../graph/GraphWrapper";
import {graphs, Graph} from "../graph/Graphs";

function HomePage() {

    return (
        <div className="container">
            {graphs.map((graph: Graph) => (
                <Wrapper key={graph.name} name={graph.name} type={graph.type} value={graph.graphValue} inputFields={graph.inputFields}/>
            ))}
        </div>
    );
}

export default HomePage
