INSERT INTO users (name, email, password, department, role) VALUES 
('Admin', 'admin@company.com', '$2y$12$91A2s4JM/cWdLT4w3cNv1uOwrrpp.xa99NWFpNjVI2hsjuGLNMyHi', 'Management', 'admin'),
('John Doe', 'john@company.com', '$2y$12$ts7zRtQ0mbmAmzCvZrKL.uBtsYOosGt9jqxQJpRc9HJ9yAx.uB7He', 'Engineering', 'user');

INSERT INTO step_templates (step_name, is_default) VALUES 
('Purchase', 1),
('PLC Programming', 1),
('Electrical Panel', 1),
('MOM Status', 1),
('Project Report', 1),
('Tax Invoice', 1);
