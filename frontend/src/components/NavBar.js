import { NavLink } from "react-router-dom";


export default function NavBar() {
	return(
		<nav className="navbar navbar-expand-lg bg-body-tertiary">
			<div className="container-fluid">
				<a className="navbar-brand" href="/">Todo App</a>

				<div className="collapse navbar-collapse" id="navbarSupportedContent">
					<ul className="navbar-nav me-auto mb-2 mb-lg-0">
						<li className="nav-item">
							<NavLink to="/" className={({ isActive }) => (isActive ? "nav-link active" : "nav-link")} aria-current="page">List</NavLink>
						</li>
						<li className="nav-item">
							<NavLink to="/new" className={({ isActive }) => (isActive ? "nav-link active" : "nav-link")}>Add</NavLink>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	);
}
