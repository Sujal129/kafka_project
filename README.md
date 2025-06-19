

## 📦 Clone the Project

```bash
git clone https://github.com/Sujal129/kafka_project/
cd kafka_project
```

> ✅  switch to the `master` branch:
```bash
git checkout master
```

---

## 🧑‍💻 Running Consumers (Using `nohup`)

```bash
nohup php consumer1.php > consumer1.log 2>&1 &
nohup php consumer2.php > consumer2.log 2>&1 &
```

### 🔍 View Logs Live

```bash
tail -f consumer1.log
tail -f consumer2.log
```

---

## 🚀 Run Producer

```bash
php producer_api.php
```

> You should now see logs in `consumer1.log` and `consumer2.log` with distributed messages.

---

## ❌ Stop Consumers (Manually)

```bash
ps aux | grep consumer1.php
sudo kill -9 <PID>

ps aux | grep consumer2.php
sudo kill -9 <PID>
```

---

## ⚙️ Run Consumers Using `systemd`

> Use the included files: `kafka-consumer1.service` and `kafka-consumer2.service`

### Step 1: Reload Daemon

```bash
sudo systemctl daemon-reexec
sudo systemctl daemon-reload
```

### Step 2: Enable on Boot

```bash
sudo systemctl enable kafka-consumer1.service
sudo systemctl enable kafka-consumer2.service
```

### Step 3: Start Consumers

```bash
sudo systemctl start kafka-consumer1.service
sudo systemctl start kafka-consumer2.service
```

### Step 4: Check Status

```bash
systemctl status kafka-consumer1.service
systemctl status kafka-consumer2.service
```

### Step 5: Restart Consumers

```bash
sudo systemctl restart kafka-consumer1.service
sudo systemctl restart kafka-consumer2.service
```

> ✅ With `systemd`, consumers:
- Start on boot
- Restart automatically on crash
- Log output to separate files
- Stay detached from the terminal

---

## 🧭 Kafka UI (by Provectus)

### Step 1: Clone the Kafka UI Repo

```bash
git clone https://github.com/provectus/kafka-ui.git
cd kafka-ui
```

### Step 2: Configure `application.yml`

```bash
mkdir config
nano config/application.yml
```

Paste the following content (update `bootstrapServers` if required):

```yaml
server:
  port: 8080

kafka:
  clusters:
    - name: local-cluster
      bootstrapServers: localhost:9092
      metrics:
        type: jmx
```

Save and exit (`CTRL+O`, `ENTER`, `CTRL+X`).

---

## 📊 Run Kafka UI

> Requires Java and Maven installed.

```bash
./gradlew bootRun
```

Open the dashboard in browser:

```
http://localhost:8080
```

You should see:
- Your `local-cluster`
- Topic `kafka_main`
- Consumers, offsets, partitions, etc.

---

## 🔧 Build and Run Backend (Optional)

```bash
cd kafka-ui-api
mvn spring-boot:run
```

##  Run Frontend (Optional)

```bash
cd ~/kafka-ui/kafka-ui-react-app
npm install
npm start
```

Open browser: `http://localhost:8080`

---

## ✅ Done!
 now have:
- Kafka producer/consumers working via PHP
- Logging and monitoring in place
- Kafka UI for real-time inspection
