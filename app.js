import express from 'express';
import swaggerUi from "swagger-ui-express";
import YAML from "yamljs";
import { 
    getBlog, getBlogs, createBlog, deleteBlog, updateBlog, createUser,
    checkUser, addAccess, removeAccess, isInAccess, isAdmin, isMyBlog 
} from './DBC.js';


const docYaml = YAML.load("./api.yaml");

const app = express();
app.use(express.json());
app.use("/api/about", swaggerUi.serve, swaggerUi.setup(docYaml));

app.get("/api/blog", async (req, res) => {
    let { username, password } = req.query || {};
    if (!username || !password || await CheckUser(username, password) < 1) {
        const blogs = await getBlogs(null); 
        return res.status(200).json(blogs);
    } else {
        const blogs = await getBlogs(username); 
        return res.status(200).json(blogs);
    }
});

app.get("/api/blog/:id", async (req, res) => {
    const id = req.params.id;
    const blog = await getBlog(id);
    if (!blog) {
        return res.status(404).json({ message: "Blog not found" });
    }
    res.status(200).json(blog);
});

app.post("/api/blog", async (req, res) => {
    const { text, date, username, password } = req.body;
    const userId = await CheckUser(username, password);
    if (userId < 1) {
        return res.status(404).json({ message: "User not found" });
    }
    await createBlog(userId, text, date);
    res.status(200).json({ message: "Blog created successfully" });
});

app.delete("/api/blog/:id", async (req, res) => {
    const id = req.params.id;
    const { username, password } = req.body;

    const blog = await getBlog(id);
    if (!blog) {
        return res.status(404).json({ message: "Blog not found" });
    }

    if (await IsAdmin(username, password) >= 1 || await IsMyBlog(id, username)) {
        await deleteBlog(id);
        return res.status(200).json({ message: "Blog deleted successfully" });
    } else {
        return res.status(403).json({ message: "Not allowed to delete this blog" });
    }
});

app.patch("/api/blog/:id", async (req, res) => {
    const id = req.params.id;
    const { text, date, username, password } = req.body;

    const blog = await getBlog(id);
    if (!blog) {
        return res.status(404).json({ message: "Blog not found" });
    }

    if (await IsAdmin(username, password) >= 1 || await IsMyBlog(id, username)) {
        await updateBlog(id, text, date);
        return res.status(200).json({ message: "Blog updated successfully" });
    } else {
        return res.status(403).json({ message: "Not allowed to update this blog" });
    }
});

app.post("/api/user", async (req, res) => {
    const { username, password } = req.body;
    await createUser(username, password);
    res.status(200).json({ message: "User created successfully" });
});

app.post("/api/access/:id", async (req, res) => {
    const { username, password, adduser } = req.body;
    const blogId = req.params.id;

    const blog = await getBlog(blogId);
    if (!blog) {
        return res.status(404).json({ message: "Blog not found" });
    }

    if (!await CheckAccessUser(adduser)) {
        return res.status(404).json({ message: "User does not exist" });
    }

    if (await IsAdmin(username, password) >= 1 || await IsMyBlog(blogId, username)) {
        await AddAccess(blogId, adduser);
        return res.status(200).json({ message: "Access added successfully" });
    } else {
        return res.status(403).json({ message: "Not allowed to add access to this blog" });
    }
});

app.delete("/api/access/:id", async (req, res) => {
    const { username, password, removeuser } = req.body;
    const blogId = req.params.id;

    const blog = await getBlog(blogId);
    if (!blog) {
        return res.status(404).json({ message: "Blog not found" });
    }

    if (!await IsInAccess(removeuser)) {
        return res.status(404).json({ message: "User does not have access to this blog" });
    }

    if (await IsAdmin(username, password) >= 1 || await IsMyBlog(blogId, username)) {
        await RemoveAccess(blogId, removeuser);
        return res.status(200).json({ message: "Access removed successfully" });
    } else {
        return res.status(403).json({ message: "Not allowed to remove access to this blog" });
    }
});

app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).json({ message: "Something went wrong!" });
});

app.listen(8080, () => {
    console.log("Server is running on port 8080");
});
