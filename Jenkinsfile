
pipeline {
    agent any
    environment {
        DISCORD_WEBHOOK_URL = 'https://discord.com/api/webhooks/1077410740115357846/MYVh1EBEDPKqhKL33353lb0vJfsLtGbuE1Sq_O21V6jr-zD1hrINEB5EzSA3XAd24AwL'
    }
    stages {
        stage('Send Notif') {
            steps {
                script {
                    env.GIT_COMMIT_MSG = sh (script: 'git log -1 --pretty=%B ${GIT_COMMIT}', returnStdout: true).trim()
                }
                discordSend description: "**Task :** ${env.GIT_COMMIT_MSG}", footer: "Start build | ${env.BRANCH_NAME}", link: env.BUILD_URL, result: "UNSTABLE", title: JOB_NAME, webhookURL: DISCORD_WEBHOOK_URL
            }
        }
        stage('Build') {
            steps {
                echo 'Building..'
           }
        }
        stage('Test') {
            steps {
              echo 'Testing..'
            }
        }
        stage('Deploy') {
            steps {
                sh 'cd /var/www/starter-laravel && ./deploy.sh'
            }
        }
    }
    post {
        failure {
            updateGitlabCommitStatus name: 'build', state: 'failed'
            discordSend description: "**Task** : ${env.GIT_COMMIT_MSG}", footer: "Status build : Gagal | ${env.BRANCH_NAME}", link: env.BUILD_URL, result: "FAILURE", title: JOB_NAME, webhookURL: DISCORD_WEBHOOK_URL
        }
        success {
            updateGitlabCommitStatus name: 'build', state: 'success'
            discordSend description: "**Task** : ${env.GIT_COMMIT_MSG}", footer: "Status build : Sukses | ${env.BRANCH_NAME}", link: env.BUILD_URL, result: "SUCCESS", title: JOB_NAME, webhookURL: DISCORD_WEBHOOK_URL
        }
        aborted {
            updateGitlabCommitStatus name: 'build', state: 'canceled'
        }
    }
}
