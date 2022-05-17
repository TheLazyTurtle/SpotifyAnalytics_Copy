import Wrapper, { GraphType, GraphValue } from "../graph/GraphWrapper";

function HomePage() {

    return (
        <div className="container">
            <Wrapper type={GraphType.Bar} value={GraphValue.allSongsPlayed}/>
            <Wrapper type={GraphType.Bar} value={GraphValue.topSongs}/>
            <Wrapper type={GraphType.Bar} value={GraphValue.topArtist}/>
            <Wrapper type={GraphType.Line} value={GraphValue.playedPerDay}/>
        </div>
    );
}

export default HomePage
