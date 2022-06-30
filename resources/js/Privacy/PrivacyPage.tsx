import { useState } from "react";
import { Modal } from "react-bootstrap";

function PrivacyComponent() {
    const [show, setShow] = useState(true);

    return (
        <Modal show={show} onHide={() => setShow(false)}>
            <Modal.Header closeButton>
                <Modal.Title>
                    Privacy statement
                </Modal.Title>
            </Modal.Header>
            <Modal.Body>
                <section>
                    <p>
                        To make this app work like intended we need to collect and store personal data.
                        The data we collect are your personal info, spotify data and some logs.
                    </p>

                    <h5>Personal data</h5>
                    <p>
                        Your personal data is the information you fill in when creating an account.
                        This is needed to confirm that you are a real person and to be able to notify you when our privacy statement changes.
                        If you wish to change or delete this information go to the settings option on your profile page.
                        If you wish to view the personal information we store about you, feel free to send a email to: <a href="mailto:spa@jcg-ict.nl">spa@jcg-ict.nl</a>.
                        This information will be stored for as long as you have a account on this website.
                    </p>

                    <h5>Spotify data</h5>
                    <p>
                        For this website to function we also collect some of you spotify data.
                        Examples of this data are spotify authentication tokens and the songs you listen to.
                        The authentication tokens are needed for the application to be able to sync your listening history with our application.
                        This application will also store the songs you have listened to and when you have listened to them.
                        This data is needed to draw the graphs you see on the website.
                        This data is only visible to you, and system administrators, unless you have set your account to public, than your data becomes public to everyone.
                        You can revoke this applications access to your spotify data using <a href="https://www.spotify.com/nl/account/apps/">this</a> link.
                        You can remove all of your listening history on this website by deleting your account in the settings menu on your profile page.
                        Your listening data can also be used for generation and recommendation purposes. When this happends your data will be made anonymous.
                        This aboved described information will be stored for as long as you have an account on this website.
                    </p>
                    <h5>Logs</h5>
                    <p>
                        For this website to be improved it will generate logs.
                        These logs contain information about the syncying of the songs you have listend to, but also about the pages you have visited,
                        the data that you fill in on inputfields, and how long each request you make takes.
                        These logs can only be viewed by system administrators.
                        These logs will be stored for a maximum of a year.
                    </p>
                </section>

            </Modal.Body>
        </Modal>
    );
}

export default PrivacyComponent;
