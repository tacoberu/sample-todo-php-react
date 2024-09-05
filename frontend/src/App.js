import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import TodoCreateForm from './components/TodoCreateForm'
import NavBar from "./components/NavBar";
import TodoList from "./components/TodoList";
import Todos from "./components/Todos";
import { TodoListProvider } from "./context/TodoContext";
import TodoDetail from "./components/TodoDetail";
import TodoUpdateForm from './components/TodoUpdateForm';


import 'bootstrap/dist/css/bootstrap.min.css'
import './assets/style/index.css';


function App() {
	return (
		<Router>
			<TodoListProvider>
				<div className="container">
					<NavBar />
					<hr />
					<Routes>
						<Route path="/new" element={<TodoCreateForm />} />
						<Route path="/" element={<Todos />}>
							<Route index element={<TodoList />} />
							<Route path=":id" element={<TodoDetail />} />
							<Route path=":id/edit" element={<TodoUpdateForm />} />
						</Route>
					</Routes>
				</div>
			</TodoListProvider>
		</Router>
	);
}

export default App;
