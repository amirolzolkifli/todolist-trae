# Modern Todo List Application

A dynamic and responsive Todo List application built with PHP, SQLite, and JavaScript. Features a modern UI with drag-and-drop functionality, filtering options, and real-time updates.

This project was created 100% by Trae AI + Sonnet 3.7

## Features

- ✨ Clean, modern interface with Bootstrap 5
- 🔄 Drag and drop task reordering
- ✅ Mark tasks as complete/incomplete
- 🗑️ Delete individual or completed tasks
- 🔍 Filter tasks (All/Active/Completed)
- 📱 Fully responsive design
- ⚡ Real-time updates without page refresh
- 🎨 Smooth animations and transitions
- 🔒 SQLite database storage

## Technologies Used

- PHP 7.4+
- SQLite3
- JavaScript (ES6+)
- Bootstrap 5
- Font Awesome 6
- SortableJS
- CSS3

## Installation

1. Clone the repository:
```bash
git clone https://github.com/amirolzolkifli/todolist-trae.git
```

2. Make sure you have PHP installed on your system (7.4 or higher)

3. Ensure SQLite3 extension is enabled in your PHP configuration

4. Place the project files in your web server directory

5. Set appropriate permissions for the SQLite database:
```bash
chmod 777 database.sqlite
```

6. Access the application through your web server

## Project Structure

```
├── api.php          # API endpoints for CRUD operations
├── db.php           # Database connection and queries
├── index.php        # Main application HTML
├── script.js        # Frontend JavaScript functionality
├── style.css        # Custom styling
└── database.sqlite  # SQLite database file
```

## Features in Detail

### Task Management
- Add new tasks
- Mark tasks as complete/incomplete
- Delete individual tasks
- Bulk complete/incomplete all tasks
- Delete all completed tasks

### Organization
- Drag and drop reordering
- Filter tasks by status
- Persistent ordering
- Real-time counter for remaining tasks

### User Interface
- Clean and intuitive design
- Responsive layout for all devices
- Smooth animations
- Custom scrollbar styling
- Confirmation modals for important actions

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- [Bootstrap](https://getbootstrap.com/)
- [Font Awesome](https://fontawesome.com/)
- [SortableJS](https://sortablejs.github.io/Sortable/)
