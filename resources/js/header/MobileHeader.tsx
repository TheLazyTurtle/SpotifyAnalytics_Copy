import "./mobileHeader.css";

interface MobileHeaderItemProps {
    href: string;
    icon: string;
}

function MobileHeaderItem({ href, icon }: MobileHeaderItemProps) {
    return (
        <div className="col-3 text-center py-2">
            <a href={href} className="text-black">
                <i className={icon} aria-hidden="true"></i>
            </a>
        </div>
    );
}

function MobileHeader() {
    return (
        <footer className="d-block d-md-none mobile-navigation">
            <div className="row">
                <MobileHeaderItem href={"/"} icon={"fas fa-home"} />
                <MobileHeaderItem href={"/search"} icon={"fas fa-search"} />
                <MobileHeaderItem href={"/notifications"} icon={"far fa-envelope"} />
                <MobileHeaderItem href={"/profile"} icon={"fas fa-user-alt"} />
            </div>
        </footer>
    );
}

export default MobileHeader;

