import Slider from "../slider/Slider";
import GraphWrapper from "../graph/GraphWrapper";
import { graphs, Graph } from "../graph/Graphs";
import axios from 'axios';
import { useEffect, useState } from "react";
import { Button, Modal, } from "react-bootstrap";

function HomePage() {
    const [show, setShow] = useState(false);

    useEffect(() => {
        axios.get("/api/spotifyTokens").then(() => setShow(false)).catch(() => setShow(true));
    }, []);


    function drawAddTokens() {
        return (
            <Modal show={show} onHide={() => setShow(false)}>
                <Modal.Header closeButton>
                    <Modal.Title>You do not have any authorization tokens registered</Modal.Title>
                </Modal.Header>

                <Modal.Body>
                    <p>
                        When making your account it appears no authorization tokens for spotify have been added in our system. This way we can not sync songs from your account. Would you like to add them now?
                    </p>
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={() => setShow(false)}>Close</Button>
                    <Button variant="primary" onClick={() => window.location.href = "/addSpotifyTokens"}>Add tokens</Button>
                </Modal.Footer>
            </Modal>
        )
    }

    return (
        <>
            {
                drawAddTokens()
            }

            < Slider />
            <section className="w-100">
                {new graphs().graphs.map((graph: Graph) => (
                    <div key={graph.name} className="graph pt-3" id={graph.name}>
                        <GraphWrapper key={graph.name} graph={graph} />
                    </div>
                ))}
            </section>
        </>
    );
}

export default HomePage
