import mysql from 'mysql2';
import dotenv from 'dotenv';
import bcrypt from 'bcrypt';
dotenv.config()

const pool = mysql.createPool({
    host: process.env.host,
    user: process.env.user,
    password: process.env.password,
    database: process.env.database,
    port: process.env.port
}).promise();

export async function getBlogs(username) {
    try {
        if (username) {
            const [rows] = await pool.query("CALL viewblogs(?)", [username]);
            return rows[0] || [];
        } else {
            const [rows] = await pool.query("SELECT * FROM blogs AS b WHERE b.id NOT IN (SELECT blogs_id FROM access)");
            return rows;
        }
    } catch (error) {
        console.error('Error fetching blogs:', error);
        return [];
    }
}

export async function getBlog(id) {
    try {
        const [rows] = await pool.query("SELECT * FROM blogs WHERE id = ?", [id]);
        return rows[0] || null;
    } catch (error) {
        console.error('Error fetching blog:', error);
        return null;
    }
}

export async function createBlog(id, text, date) {
    try {
        let result;
        if (date) {
            [result] = await pool.query("INSERT INTO blogs (uzivatel_id, text, date) VALUES (?, ?, ?)", [id, text, date]);
        } else {
            [result] = await pool.query("INSERT INTO blogs (uzivatel_id, text) VALUES (?, ?)", [id, text]);
        }
        return result.insertId;
    } catch (error) {
        console.error('Error creating blog:', error);
        throw error;
    }
}

export async function deleteBlog(id) {
    try {
        const [result] = await pool.query("DELETE FROM blogs WHERE id = ?", [id]);
        return result;
    } catch (error) {
        console.error('Error deleting blog:', error);
        throw error;
    }
}

export async function updateBlog(id, text, date) {
    try {
        const currentBlog = await getBlog(id);
        if (!currentBlog) return null; 
        
        text = text || currentBlog.text;
        date = date || currentBlog.date;

        const [result] = await pool.query("UPDATE blogs SET text = ?, date = ? WHERE id = ?", [text, date, id]);
        return result;
    } catch (error) {
        console.error('Error updating blog:', error);
        throw error;
    }
}

export async function checkUser(username, password) {
    try {
        if (!username || !password) return null;

        const [rows] = await pool.query("SELECT password, id FROM uzivatel WHERE username = ?", [username]);
        if (rows.length === 0) return null;

        const passwordHash = rows[0].password;
        const isMatch = await bcrypt.compare(password, passwordHash);

        return isMatch ? rows[0].id : null;
    } catch (error) {
        console.error('Error checking user:', error);
        return null;
    }
}

export async function createUser(username, password) {
    try {
        const passwordHash = await bcrypt.hash(password, 10);
        const [result] = await pool.query("INSERT INTO uzivatel (username, password) VALUES (?, ?)", [username, passwordHash]);
        return result.insertId;
    } catch (error) {
        console.error('Error creating user:', error);
        throw error;
    }
}

export async function addAccess(id, user) {
    try {
        const [result] = await pool.query("CALL addaccess (?, ?)", [id, user]);
        return result;
    } catch (error) {
        console.error('Error adding access:', error);
        throw error;
    }
}


export async function removeAccess(id, user) {
    try {
        const [result] = await pool.query("DELETE FROM access WHERE blogs_id = ? AND uzivatel_id = (SELECT id FROM uzivatel WHERE username = ?)", [id, user]);
        return result;
    } catch (error) {
        console.error('Error removing access:', error);
        throw error;
    }
}

export async function isInAccess(user) {
    try {
        const [rows] = await pool.query("SELECT * FROM access WHERE uzivatel_id IN (SELECT id FROM uzivatel WHERE username = ?)", [user]);
        return rows.length > 0;
    } catch (error) {
        console.error('Error checking access:', error);
        return false;
    }
}

export async function isAdmin(user, password) {
    try {
        const [rows] = await pool.query("SELECT password, id FROM uzivatel WHERE username = ? AND admin = 1", [user]);
        if (rows.length === 0) return null;

        const passwordHash = rows[0].password;
        const isMatch = await bcrypt.compare(password, passwordHash);

        return isMatch ? rows[0].id : null;
    } catch (error) {
        console.error('Error checking admin:', error);
        return null;
    }
}

export async function isMyBlog(id, user) {
    try {
        const [rows] = await pool.query("SELECT * FROM blogs WHERE id = ? AND uzivatel_id IN (SELECT id FROM uzivatel WHERE username = ?)", [id, user]);
        return rows.length > 0;
    } catch (error) {
        console.error('Error checking if user owns blog:', error);
        return false;
    }
}
export async function checkAccessUser(username) {
    try {
        const [rows] = await pool.query("SELECT * FROM access WHERE uzivatel_id IN (SELECT id FROM uzivatel WHERE username = ?)", [username]);
        return rows.length > 0;
    } catch (error) {
        console.error('Error checking access for user:', error);
        return false;
    }
}

